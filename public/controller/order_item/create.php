<?php

require '../../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use app\database\Connection;

if (isset($_SERVER['HTTP_ORIGIN'])) {
    header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');
}

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS");
header('Access-Control-Allow-Headers: Authorization, Content-Type, x-xsrf-token, x_csrftoken, Cache-Control, X-Requested-With');

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__, 4));
$dotenv->load();

$authorization = $_SERVER['HTTP_AUTHORIZATION'];
$token = str_replace('Bearer ', '', $authorization);

include_once '../../../app/database/Connection.php';
include_once '../../model/order_item.php';
include_once '../../model/material.php';
include_once '../../model/material_historical.php';
include_once '../../model/expense.php';
include_once '../../utils/material_historical_expense.php';

$conn = new Connection();
$db = $conn->connect();

try {
    $decoded = JWT::decode($token, new Key($_SERVER['KEY'], 'HS256'));

    $orderItem = new OrderItem($db);
    $material = new Material($db);
    $materialHistorical = new MaterialHistorical($db);
    $expense = new Expense($db);

    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = dirname(__FILE__, 3) . '/wwwroot/images/';

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $extension = pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('img_', true) . '.' . $extension;
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $targetPath)) {
            $image_file = $fileName;
            $image_path = 'wwwroot/images/' . $fileName;
        } else {
            http_response_code(500);
            die('Error uploading file');
        }
    } else {
        $image_file = null;
        $image_path = null;
    }

    $companyId = isset($_POST['company_id']) ? (int) $_POST['company_id'] : null;

    if (empty($companyId)) {
        echo json_encode(array("message" => "missing_company_id"));
        exit;
    }

    $orderId = isset($_POST['order_id']) ? (int) $_POST['order_id'] : null;

    if (empty($orderId)) {
        echo json_encode(array("message" => "missing_order_id"));
        exit;
    }

    $materialId = null;
    if (isset($_POST['material_id']) && $_POST['material_id'] !== '') {
        $materialId = (int) $_POST['material_id'];
    }

    $data = [
        'company_id' => $companyId,
        'order_id' => $orderId,
        'material_id' => $materialId,
        'description' => $_POST['description'] ?? null,
        'quantity' => $_POST['quantity'] ?? null,
        'width' => $_POST['width'] ?? null,
        'height' => $_POST['height'] ?? null,
        'color' => $_POST['color'] ?? null,
        'unit_cost' => $_POST['unit_cost'] ?? null,
        'total' => $_POST['total'] ?? null,
        'discount_for_stock' => $_POST['discount_for_stock'] ?? null,
        'image_file' => $image_file,
        'image_path' => $image_path,
        'created_user_id' => $_POST['created_user_id'] ?? null,
        'created_date' => $_POST['created_date'] ?? null,
        'updated_user_id' => $_POST['updated_user_id'] ?? null,
        'updated_date' => $_POST['updated_date'] ?? null
    ];

    /**
     * @param array<string, mixed> $mat Row from Material::getById
     * @param int|string $stockValue Recorded stock column for historical (decimal string acceptable)
     * @param mixed|null $overrideUnitCost When non-null (e.g. order line selling price), use instead of material unit_cost
     * @param mixed|null $quantityOverride When non-null, use for material_historical.quantity instead of full order quantity
     * @return array<string, mixed>
     */
    $buildHistoricalFromMaterial = function (
        $mat,
        int $orderItemId,
        string $movementType,
        $stockValue,
        $overrideUnitCost = null,
        $quantityOverride = null
    ) use ($data) {
        return [
            'material_id' => (int) $mat['id'],
            'company_id' => (int) $mat['company_id'],
            'supplier_id' => isset($mat['supplier_id']) && $mat['supplier_id'] !== '' && $mat['supplier_id'] !== null
                ? (int) $mat['supplier_id']
                : null,
            'order_item_id' => $orderItemId,
            'explanation' => $mat['description'],
            'quantity' => $quantityOverride !== null ? $quantityOverride : $data['quantity'],
            'unit_cost' => $overrideUnitCost !== null ? $overrideUnitCost : $mat['unit_cost'],
            'sales_price' => $mat['sale_price'],
            'stock' => $stockValue,
            'movement_type' => $movementType,
            'created_user_id' => $data['created_user_id'],
            'created_date' => $data['created_date'],
            'updated_user_id' => $data['updated_user_id'],
            'updated_date' => $data['updated_date'],
        ];
    };

    $qty = isset($data['quantity']) ? (float) $data['quantity'] : 0.0;

    $db->beginTransaction();

    if (!$orderItem->create($data)) {
        $db->rollBack();
        echo json_encode(array("message" => "error_creating_record"));
        exit;
    }

    $newOrderItemId = (int) $db->lastInsertId();

    if ($materialId !== null && $qty > 0) {
        $matRow = $material->getById($materialId);
        if (!$matRow) {
            $db->rollBack();
            echo json_encode(array("message" => "material_not_found"));
            exit;
        }

        if ((int) $matRow['company_id'] !== (int) $companyId) {
            $db->rollBack();
            echo json_encode(array("message" => "material_company_mismatch"));
            exit;
        }

        $availableStock = max(0.0, (float) ($matRow['stock'] ?? 0));

        $materialUpdate = [
            'id' => $matRow['id'],
            'company_id' => $matRow['company_id'],
            'supplier_id' => $matRow['supplier_id'],
            'name' => $matRow['name'],
            'description' => $matRow['description'],
            'unit_cost' => $matRow['unit_cost'],
            'sale_price' => $matRow['sale_price'],
            'image_file' => $matRow['image_file'],
            'image_path' => $matRow['image_path'],
            'updated_user_id' => $data['updated_user_id'],
            'updated_date' => $data['updated_date'],
        ];

        if ($qty <= $availableStock) {
            $newMaterialStock = $availableStock - $qty;
            $materialUpdate['stock'] = $newMaterialStock;

            if (!$material->update($materialUpdate)) {
                $db->rollBack();
                echo json_encode(array("message" => "error_updating_stock"));
                exit;
            }

            $histExit = $buildHistoricalFromMaterial($matRow, $newOrderItemId, 'EXIT', $newMaterialStock);
            if (!$materialHistorical->create($histExit)) {
                $db->rollBack();
                echo json_encode(array("message" => "error_creating_record"));
                exit;
            }
        } else {
            $materialUpdate['stock'] = 0;

            if (!$material->update($materialUpdate)) {
                $db->rollBack();
                echo json_encode(array("message" => "error_updating_stock"));
                exit;
            }

            $shortage = $qty - $availableStock;

            $histExit = $buildHistoricalFromMaterial(
                $matRow,
                $newOrderItemId,
                'EXIT',
                0,
                null,
                $availableStock
            );
            if (!$materialHistorical->create($histExit)) {
                $db->rollBack();
                echo json_encode(array("message" => "error_creating_record"));
                exit;
            }

            $orderLineUnitPrice = array_key_exists('unit_price', $_POST)
                ? $_POST['unit_price']
                : $data['unit_cost'];
                
            $histBought = $buildHistoricalFromMaterial(
                $matRow,
                $newOrderItemId,
                'BOUGHT TRHOUGH STORE',
                0,
                $orderLineUnitPrice,
                $shortage
            );
            if (!$materialHistorical->create($histBought)) {
                $db->rollBack();
                echo json_encode(array("message" => "error_creating_record"));
                exit;
            }

            $expenseAudit = [
                'created_user_id' => $data['created_user_id'],
                'created_date' => $data['created_date'],
                'updated_user_id' => $data['updated_user_id'],
                'updated_date' => $data['updated_date'],
            ];

            if (!material_historical_expense_sync_on_create($expense, $db, $material, $histBought, $expenseAudit)) {
                $db->rollBack();
                echo json_encode(array("message" => "error_creating_record"));
                exit;
            }
        }
    }

    $db->commit();

    echo json_encode(['order_item' => []]);
} catch (Throwable $e) {
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    http_response_code(401);
    die('EXPIRED' . $e);
}

?>
