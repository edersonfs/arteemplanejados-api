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
include_once '../../model/material_historical.php';
include_once '../../model/material.php';
include_once '../../model/expense.php';
include_once '../../utils/material_historical_expense.php';

$conn = new Connection();
$db = $conn->connect();

try {
    $decoded = JWT::decode($token, new Key($_SERVER['KEY'], 'HS256'));

    $materialHistorical = new MaterialHistorical($db);
    $material = new Material($db);
    $expense = new Expense($db);

    $materialId = isset($_POST['material_id']) ? (int) $_POST['material_id'] : null;

    if (empty($materialId)) {
        echo json_encode(array("message" => "missing_material_id"));
        exit;
    }

    $companyId = isset($_POST['company_id']) ? (int) $_POST['company_id'] : null;

    if (empty($companyId)) {
        echo json_encode(array("message" => "missing_company_id"));
        exit;
    }

    $supplierId = null;
    if (isset($_POST['supplier_id']) && $_POST['supplier_id'] !== '') {
        $supplierId = (int) $_POST['supplier_id'];
    }

    $orderItemId = null;
    if (isset($_POST['order_item_id']) && $_POST['order_item_id'] !== '') {
        $orderItemId = (int) $_POST['order_item_id'];
    }

    $movementType = $_POST['movement_type'] ?? null;

    if ($movementType === null || $movementType === '') {
        echo json_encode(array("message" => "missing_movement_type"));
        exit;
    }

    $data = [
        'material_id' => $materialId,
        'company_id' => $companyId,
        'supplier_id' => $supplierId,
        'order_item_id' => $orderItemId,
        'explanation' => $_POST['explanation'] ?? null,
        'quantity' => $_POST['quantity'] ?? null,
        'unit_cost' => $_POST['unit_cost'] ?? null,
        'sales_price' => $_POST['sales_price'] ?? null,
        'stock' => $_POST['stock'] ?? null,
        'movement_type' => $movementType,
        'created_user_id' => $_POST['created_user_id'] ?? null,
        'created_date' => $_POST['created_date'] ?? null,
        'updated_user_id' => $_POST['updated_user_id'] ?? null,
        'updated_date' => $_POST['updated_date'] ?? null
    ];

    $audit = [
        'created_user_id' => $data['created_user_id'],
        'created_date' => $data['created_date'],
        'updated_user_id' => $data['updated_user_id'],
        'updated_date' => $data['updated_date'],
    ];

    $db->beginTransaction();

    if (!$materialHistorical->create($data)) {
        $db->rollBack();
        echo json_encode(array("message" => "error_creating_record"));
        exit;
    }

    if (!material_historical_expense_sync_on_create($expense, $db, $material, $data, $audit)) {
        $db->rollBack();
        echo json_encode(array("message" => "error_creating_record"));
        exit;
    }

    $db->commit();
    echo json_encode(['material_historical' => []]);
} catch (Throwable $e) {
    http_response_code(401);
    die('EXPIRED');
}

?>
