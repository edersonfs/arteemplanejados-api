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
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, DELETE");

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

$conn = new Connection();
$db = $conn->connect();

try {
  $decoded = JWT::decode($token, new Key($_SERVER['KEY'], 'HS256'));

  $orderItem = new OrderItem($db);
  $material = new Material($db);
  $materialHistorical = new MaterialHistorical($db);
  $expense = new Expense($db);

  $id = filter_input(INPUT_GET, 'id', FILTER_DEFAULT);

  if (!$id) {
    echo json_encode(array("message" => "missing_data_id"));
    exit;
  }

  $orderItemId = (int) $id;
  $orderItemRow = $orderItem->getById($orderItemId);

  if (!$orderItemRow) {
    echo json_encode(array("message" => "record_does_not_exist"));
    exit;
  }

  $auditUserId = $_POST['updated_user_id'] ?? $_POST['created_user_id']
    ?? $orderItemRow['updated_user_id'] ?? $orderItemRow['created_user_id'] ?? null;
  $auditDate = $_POST['updated_date'] ?? $_POST['created_date']
    ?? $orderItemRow['updated_date'] ?? $orderItemRow['created_date'] ?? date('Y-m-d H:i:s');

  $db->beginTransaction();

  $latestExitRow = $materialHistorical->findLatestExitByOrderItemId($orderItemId);

  $linkedHistorical = $materialHistorical->getRowsByOrderItemId($orderItemId);

  if ($latestExitRow) {
    $latestExitStock = $materialHistorical->findLatestExitById((int) $latestExitRow['id']);
  } else {
    $latestExitStock = 0;
  }

  foreach ($linkedHistorical as $histRow) {
    $updatePayload = [
      'id' => (int) $histRow['id'],
      'material_id' => (int) $histRow['material_id'],
      'company_id' => (int) $histRow['company_id'],
      'supplier_id' => $histRow['supplier_id'],
      'order_item_id' => null,
      'explanation' => $histRow['explanation'],
      'quantity' => $histRow['quantity'],
      'unit_cost' => $histRow['unit_cost'],
      'sales_price' => $histRow['sales_price'],
      'stock' => $histRow['stock'],
      'movement_type' => 'REMOVE',
      'updated_user_id' => $auditUserId,
      'updated_date' => $auditDate,
    ];

    if (!$materialHistorical->update($updatePayload)) {
      $db->rollBack();
      echo json_encode(array("message" => "error_updating_record"));
      exit;
    }
  }

  if ($latestExitRow) {
    $restoredStock = (float) ((int) ($latestExitRow['quantity'] ?? 0) + $latestExitStock);
    $exitQuantity = $restoredStock;

    if ($exitQuantity > 0) {
      $materialId = (int) $latestExitRow['material_id'];
      $matRow = $material->getById($materialId);

      if (!$matRow) {
        $db->rollBack();
        echo json_encode(array("message" => "material_not_found"));
        exit;
      }

      $materialUpdate = [
        'id' => $matRow['id'],
        'company_id' => $matRow['company_id'],
        'supplier_id' => $matRow['supplier_id'],
        'material_type_id' => $matRow['material_type_id'] ?? null,
        'name' => $matRow['name'],
        'description' => $matRow['description'],
        'unit_cost' => $matRow['unit_cost'],
        'sale_price' => $matRow['sale_price'],
        'stock' => $restoredStock,
        'image_file' => $matRow['image_file'],
        'image_path' => $matRow['image_path'],
        'updated_user_id' => $auditUserId,
        'updated_date' => $auditDate,
      ];

      if (!$material->update($materialUpdate)) {
        $db->rollBack();
        echo json_encode(array("message" => "error_updating_stock"));
        exit;
      }

      $supplierId = isset($latestExitRow['supplier_id']) && $latestExitRow['supplier_id'] !== '' && $latestExitRow['supplier_id'] !== null
        ? (int) $latestExitRow['supplier_id']
        : null;

      $adjustmentHistorical = [
        'material_id' => $materialId,
        'company_id' => (int) $latestExitRow['company_id'],
        'supplier_id' => $supplierId,
        'order_item_id' => null,
        'explanation' => $latestExitRow['explanation'],
        'quantity' => $exitQuantity,
        'unit_cost' => $latestExitRow['unit_cost'],
        'sales_price' => $latestExitRow['sales_price'],
        'stock' => $restoredStock,
        'movement_type' => 'ADJUSTMENT',
        'created_user_id' => $auditUserId,
        'created_date' => $auditDate,
        'updated_user_id' => $auditUserId,
        'updated_date' => $auditDate,
      ];

      if (!$materialHistorical->create($adjustmentHistorical)) {
        $db->rollBack();
        echo json_encode(array("message" => "error_creating_record"));
        exit;
      }
    }
  }

  if (!$expense->deleteByOrderItemId($orderItemId)) {
    $db->rollBack();
    echo json_encode(array("message" => "error"));
    exit;
  }

  if (!$orderItem->delete($orderItemId)) {
    $db->rollBack();
    echo json_encode(array("message" => "error"));
    exit;
  }

  $db->commit();
  echo json_encode(array("message" => "success"));
} catch (Throwable $e) {
  if (isset($db) && $db->inTransaction()) {
    $db->rollBack();
  }
  http_response_code(401);
  die('EXPIRED' . $e);
}
