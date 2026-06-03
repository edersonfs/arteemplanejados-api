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

  $oldRow = $materialHistorical->getById($_POST['id']);
  if (!$oldRow) {
    echo json_encode(array("message" => "record_does_not_exist"));
    exit;
  }

  $materialId = isset($_POST['material_id']) ? (int) $_POST['material_id'] : (int) $oldRow['material_id'];
  $companyId = isset($_POST['company_id']) ? (int) $_POST['company_id'] : (int) $oldRow['company_id'];

  $supplierId = null;
  if (array_key_exists('supplier_id', $_POST)) {
    if ($_POST['supplier_id'] === '' || $_POST['supplier_id'] === null) {
      $supplierId = null;
    } else {
      $supplierId = (int) $_POST['supplier_id'];
    }
  } else {
    $sid = $oldRow['supplier_id'] ?? null;
    $supplierId = ($sid === null || $sid === '') ? null : (int) $sid;
  }

  $orderItemId = null;
  if (array_key_exists('order_item_id', $_POST)) {
    if ($_POST['order_item_id'] === '' || $_POST['order_item_id'] === null) {
      $orderItemId = null;
    } else {
      $orderItemId = (int) $_POST['order_item_id'];
    }
  } else {
    $oid = $oldRow['order_item_id'] ?? null;
    $orderItemId = ($oid === null || $oid === '') ? null : (int) $oid;
  }

  $movementType = array_key_exists('movement_type', $_POST)
    ? $_POST['movement_type']
    : $oldRow['movement_type'];

  if ($movementType === null || $movementType === '') {
    echo json_encode(array("message" => "missing_movement_type"));
    exit;
  }

  $data = [
    'id' => $_POST['id'] ?? null,
    'material_id' => $materialId,
    'company_id' => $companyId,
    'supplier_id' => $supplierId,
    'order_item_id' => $orderItemId,
    'explanation' => $_POST['explanation'] ?? $oldRow['explanation'],
    'quantity' => $_POST['quantity'] ?? $oldRow['quantity'],
    'unit_cost' => $_POST['unit_cost'] ?? $oldRow['unit_cost'],
    'sales_price' => $_POST['sales_price'] ?? $oldRow['sales_price'],
    'stock' => $_POST['stock'] ?? $oldRow['stock'],
    'movement_type' => $movementType,
    'updated_user_id' => $_POST['updated_user_id'] ?? $oldRow['updated_user_id'],
    'updated_date' => $_POST['updated_date'] ?? $oldRow['updated_date']
  ];

  $audit = [
    'created_user_id' => $oldRow['created_user_id'] ?? null,
    'created_date' => $oldRow['created_date'] ?? null,
    'updated_user_id' => $data['updated_user_id'],
    'updated_date' => $data['updated_date'],
  ];

  $db->beginTransaction();

  if (!$materialHistorical->update($data)) {
    $db->rollBack();
    echo json_encode(array("message" => "error_updating_record"));
    exit;
  }

  if (!material_historical_expense_sync_on_update($expense, $db, $material, $data, $audit)) {
    $db->rollBack();
    echo json_encode(array("message" => "error_updating_record"));
    exit;
  }

  $db->commit();
  echo json_encode(['material_historical' => []]);
} catch (Throwable $e) {
  http_response_code(401);
  die('EXPIRED');
}
