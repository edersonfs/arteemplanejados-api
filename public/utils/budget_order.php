<?php

require_once dirname(__FILE__) . '/../model/order.php';
require_once dirname(__FILE__) . '/../model/budget_item.php';

function budget_order_is_approved(?string $status): bool
{
  return strcasecmp(trim((string) $status), 'Approved') === 0;
}

function budget_order_sync_if_approved(Order $order, int $budgetId, array $budgetRow, PDO $db): bool
{
  if (!budget_order_is_approved($budgetRow['status'] ?? null)) {
    return true;
  }

  if ($order->existsByBudgetId($budgetId)) {
    return true;
  }

  $internalClientId = $budgetRow['internal_client_id'] ?? null;

  if ($internalClientId === '') {
    $internalClientId = null;
  }

  $budgetItem = new BudgetItem($db);
  $fixedCost = $budgetItem->fixedCostByBudgetId($budgetId);
  $materialAndLaborTotal = $budgetItem->sumMaterialAndLaborTotalByBudgetId($budgetId);
  $freight = $budgetItem->sumFreightTotalByBudgetId($budgetId);

  $orderData = [
    'company_id' => (int) $budgetRow['company_id'],
    'internal_client_id' => $internalClientId,
    'budget_id' => $budgetId,
    'number' => '1',
    'status' => 'scheduled',
    'start_date' => null,
    'install_date' => null,
    'delivery_date' => null,
    'fixed_cost' => (($materialAndLaborTotal * $fixedCost) / 100),
    'freight' => $freight,
    'total' => $budgetRow['sale'] ?? null,
    'notes' => null,
    'priority' => null,
    'estimated_days' => null,
    'image_file' => null,
    'image_path' => null,
    'created_user_id' => $budgetRow['created_user_id'] ?? $budgetRow['updated_user_id'] ?? null,
    'created_date' => $budgetRow['created_date'] ?? $budgetRow['updated_date'] ?? null,
    'updated_user_id' => $budgetRow['updated_user_id'] ?? null,
    'updated_date' => $budgetRow['updated_date'] ?? null,
  ];

  return $order->create($orderData);
}
