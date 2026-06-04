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
include_once '../../model/material.php';
include_once '../../model/material_historical.php';

$conn = new Connection();
$db = $conn->connect();

try {
  $decoded = JWT::decode($token, new Key($_SERVER['KEY'], 'HS256'));

  $material = new Material($db);
  $materialHistorical = new MaterialHistorical($db);

  $materialId = isset($_POST['id']) ? (int) $_POST['id'] : null;

  if (empty($materialId)) {
    echo json_encode(array("message" => "missing_data_id"));
    exit;
  }

  $oldRow = $material->getById($materialId);
  if (!$oldRow) {
    echo json_encode(array("message" => "record_does_not_exist"));
    exit;
  }

  $stockToRemove = isset($_POST['stock']) ? (float) $_POST['stock'] : 0.0;

  if ($stockToRemove <= 0) {
    echo json_encode(array("message" => "missing_stock"));
    exit;
  }

  $currentStock = max(0.0, (float) ($oldRow['stock'] ?? 0));

  if ($stockToRemove > $currentStock) {
    echo json_encode(array("message" => "insufficient_stock"));
    exit;
  }

  $image_file = $oldRow['image_file'];
  $image_path = $oldRow['image_path'];

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
  }

  $companyId = isset($_POST['company_id']) ? (int) $_POST['company_id'] : (int) $oldRow['company_id'];
  $supplierId = isset($_POST['supplier_id']) ? (int) $_POST['supplier_id'] : (int) $oldRow['supplier_id'];

  if (array_key_exists('material_type_id', $_POST)) {
    if ($_POST['material_type_id'] === '' || $_POST['material_type_id'] === null) {
      $materialTypeId = null;
    } else {
      $materialTypeId = (int) $_POST['material_type_id'];
    }
  } else {
    $mtid = $oldRow['material_type_id'] ?? null;
    $materialTypeId = ($mtid === null || $mtid === '') ? null : (int) $mtid;
  }

  $newStock = $currentStock - $stockToRemove;

  $description = array_key_exists('description', $_POST)
    ? $_POST['description']
    : $oldRow['description'];

  $unitCost = array_key_exists('unit_cost', $_POST)
    ? $_POST['unit_cost']
    : $oldRow['unit_cost'];

  $salePrice = array_key_exists('sale_price', $_POST)
    ? $_POST['sale_price']
    : $oldRow['sale_price'];

  $data = [
    'id' => $materialId,
    'company_id' => $companyId,
    'supplier_id' => $supplierId,
    'material_type_id' => $materialTypeId,
    'name' => $_POST['name'] ?? $oldRow['name'],
    'description' => $description,
    'unit_cost' => $unitCost,
    'sale_price' => $salePrice,
    'stock' => $newStock,
    'image_file' => $image_file,
    'image_path' => $image_path,
    'updated_user_id' => $_POST['updated_user_id'] ?? null,
    'updated_date' => $_POST['updated_date'] ?? null
  ];

  $movementType = $_POST['movement_type'] ?? 'EXIT';

  $historicalData = [
    'material_id' => $materialId,
    'company_id' => $companyId,
    'supplier_id' => $supplierId,
    'order_item_id' => null,
    'explanation' => $description,
    'quantity' => $stockToRemove,
    'unit_cost' => $unitCost,
    'sales_price' => $salePrice,
    'stock' => $newStock,
    'movement_type' => $movementType,
    'created_user_id' => $_POST['updated_user_id'] ?? $_POST['created_user_id'] ?? null,
    'created_date' => $_POST['updated_date'] ?? $_POST['created_date'] ?? null,
    'updated_user_id' => $_POST['updated_user_id'] ?? null,
    'updated_date' => $_POST['updated_date'] ?? null
  ];

  $db->beginTransaction();

  if (!$material->update($data)) {
    $db->rollBack();
    echo json_encode(array("message" => "error_updating_record"));
    exit;
  }

  if (!$materialHistorical->create($historicalData)) {
    $db->rollBack();
    echo json_encode(array("message" => "error_creating_record"));
    exit;
  }

  $db->commit();

  echo json_encode([
    'material' => [
      'stock_removed' => $stockToRemove,
      'stock' => $newStock
    ]
  ]);
} catch (Throwable $e) {
  if (isset($db) && $db->inTransaction()) {
    $db->rollBack();
  }
  http_response_code(401);
  die('EXPIRED');
}
