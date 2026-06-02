<?php

require_once dirname(__FILE__) . '/../model/expense_type.php';
require_once dirname(__FILE__) . '/../model/order_item.php';

const MATERIAL_PURCHASE_EXPENSE_TYPE_NAME = 'Material Purchase';

function material_historical_expense_normalize_movement(string $movementType): string
{
    $normalized = strtoupper(trim($movementType));

    if ($normalized === 'ADJUSMENT') {
        return 'ADJUSTMENT';
    }

    return $normalized;
}

function material_historical_expense_is_bought_through_store(string $movementType): bool
{
    return strtoupper(trim($movementType)) === 'BOUGHT TRHOUGH STORE';
}

function material_historical_expense_should_sync(string $movementType): bool
{
    if (material_historical_expense_is_bought_through_store($movementType)) {
        return true;
    }

    $movement = material_historical_expense_normalize_movement($movementType);

    return $movement === 'ENTRY' || $movement === 'ADJUSTMENT';
}

function material_historical_expense_type_id(PDO $db): ?int
{
    static $cached = null;

    if ($cached !== null) {
        return $cached;
    }

    $expenseType = new ExpenseType($db);
    $cached = $expenseType->getIdByName(MATERIAL_PURCHASE_EXPENSE_TYPE_NAME);

    return $cached;
}

function material_historical_expense_value(array $historicalRow): float
{
    $unitCost = (float) ($historicalRow['unit_cost'] ?? 0);
    $quantity = (float) ($historicalRow['quantity'] ?? 0);

    return $unitCost * $quantity;
}

function material_historical_expense_resolve_order_id(
    PDO $db,
    array $historicalRow
): ?int {
    $orderItemId = $historicalRow['order_item_id'] ?? null;

    if ($orderItemId === null || $orderItemId === '') {
        return null;
    }

    $orderItem = new OrderItem($db);
    $orderItemRow = $orderItem->getById((int) $orderItemId);

    if (!$orderItemRow) {
        return null;
    }

    $orderId = $orderItemRow['order_id'] ?? null;

    return ($orderId === null || $orderId === '') ? null : (int) $orderId;
}

function material_historical_expense_build_payload(
    array $historicalRow,
    array $materialRow,
    int $expenseTypeId,
    array $audit,
    PDO $db,
    ?array $existingExpense = null
): array {
    $today = date('Y-m-d');
    $orderItemId = $historicalRow['order_item_id'] ?? null;

    return [
        'company_id' => (int) $historicalRow['company_id'],
        'order_id' => material_historical_expense_resolve_order_id($db, $historicalRow),
        'order_item_id' => ($orderItemId === null || $orderItemId === '') ? null : (int) $orderItemId,
        'supplier_id' => $historicalRow['supplier_id'] ?? null,
        'material_id' => (int) $historicalRow['material_id'],
        'expense_type_id' => $expenseTypeId,
        'description' => $materialRow['name'],
        'quantity' => $historicalRow['quantity'],
        'value' => material_historical_expense_value($historicalRow),
        'expense_date' => $today,
        'payment_date' => $today,
        'status' => 'PAID',
        'image_file' => $existingExpense['image_file'] ?? null,
        'image_path' => $existingExpense['image_path'] ?? null,
        'created_user_id' => $audit['created_user_id'] ?? $audit['updated_user_id'] ?? null,
        'created_date' => $audit['created_date'] ?? $audit['updated_date'] ?? $today,
        'updated_user_id' => $audit['updated_user_id'] ?? null,
        'updated_date' => $audit['updated_date'] ?? $today,
    ];
}

function material_historical_expense_insert(
    Expense $expense,
    PDO $db,
    Material $material,
    array $historicalRow,
    array $audit
): bool {
    $expenseTypeId = material_historical_expense_type_id($db);

    if ($expenseTypeId === null) {
        return false;
    }

    $materialRow = $material->getById((int) $historicalRow['material_id']);

    if (!$materialRow) {
        return false;
    }

    $payload = material_historical_expense_build_payload(
        $historicalRow,
        $materialRow,
        $expenseTypeId,
        $audit,
        $db
    );

    return $expense->create($payload);
}

function material_historical_expense_update_linked(
    Expense $expense,
    PDO $db,
    Material $material,
    array $historicalRow,
    array $audit
): bool {
    $expenseTypeId = material_historical_expense_type_id($db);

    if ($expenseTypeId === null) {
        return false;
    }

    $materialRow = $material->getById((int) $historicalRow['material_id']);

    if (!$materialRow) {
        return false;
    }

    $materialId = (int) $historicalRow['material_id'];
    $orderItemId = $historicalRow['order_item_id'] ?? null;
    $expenseId = null;

    if ($orderItemId !== null && $orderItemId !== '') {
        $expenseId = $expense->findMaterialPurchaseExpenseIdByOrderItemId((int) $orderItemId, $expenseTypeId);
    }

    if ($expenseId === null) {
        $expenseId = $expense->findMaterialPurchaseExpenseId($materialId, $expenseTypeId);
    }

    if ($expenseId === null) {
        return material_historical_expense_insert($expense, $db, $material, $historicalRow, $audit);
    }

    $existing = $expense->getById($expenseId);
    $payload = material_historical_expense_build_payload(
        $historicalRow,
        $materialRow,
        $expenseTypeId,
        $audit,
        $db,
        $existing
    );
    $payload['id'] = $expenseId;

    return $expense->update($payload);
}

function material_historical_expense_sync_on_create(
    Expense $expense,
    PDO $db,
    Material $material,
    array $historicalRow,
    array $audit
): bool {
    if (!material_historical_expense_should_sync($historicalRow['movement_type'])) {
        return true;
    }

    $movement = material_historical_expense_normalize_movement($historicalRow['movement_type']);

    if ($movement === 'ENTRY' || material_historical_expense_is_bought_through_store($historicalRow['movement_type'])) {
        return material_historical_expense_insert($expense, $db, $material, $historicalRow, $audit);
    }

    return material_historical_expense_update_linked($expense, $db, $material, $historicalRow, $audit);
}

function material_historical_expense_sync_on_update(
    Expense $expense,
    PDO $db,
    Material $material,
    array $historicalRow,
    array $audit
): bool {
    if (!material_historical_expense_should_sync($historicalRow['movement_type'])) {
        return true;
    }

    return material_historical_expense_update_linked($expense, $db, $material, $historicalRow, $audit);
}

?>