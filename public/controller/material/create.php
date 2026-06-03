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
include_once '../../model/expense.php';
include_once '../../utils/material_historical_expense.php';

$conn = new Connection();
$db = $conn->connect();

try {
  $decoded = JWT::decode($token, new Key($_SERVER['KEY'], 'HS256'));

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

  $supplierId = isset($_POST['supplier_id']) ? (int) $_POST['supplier_id'] : null;

  if (empty($supplierId)) {
    echo json_encode(array("message" => "missing_supplier_id"));
    exit;
  }

  $materialTypeId = null;
  if (isset($_POST['material_type_id']) && $_POST['material_type_id'] !== '') {
    $materialTypeId = (int) $_POST['material_type_id'];
  }

  $data = [
    'company_id' => $companyId,
    'supplier_id' => $supplierId,
    'material_type_id' => $materialTypeId,
    'name' => $_POST['name'] ?? null,
    'description' => $_POST['description'] ?? null,
    'unit_cost' => $_POST['unit_cost'] ?? null,
    'sale_price' => $_POST['sale_price'] ?? null,
    'stock' => $_POST['stock'] ?? null,
    'image_file' => $image_file,
    'image_path' => $image_path,
    'created_user_id' => $_POST['created_user_id'] ?? null,
    'created_date' => $_POST['created_date'] ?? null,
    'updated_user_id' => $_POST['updated_user_id'] ?? null,
    'updated_date' => $_POST['updated_date'] ?? null
  ];

  $db->beginTransaction();

  if (!$material->create($data)) {
    $db->rollBack();
    echo json_encode(array("message" => "error_creating_record"));
    exit;
  }

  $newMaterialId = (int) $db->lastInsertId();

  $historicalData = [
    'material_id' => $newMaterialId,
    'company_id' => $companyId,
    'supplier_id' => $supplierId,
    'order_item_id' => null,
    'explanation' => $data['description'],
    'quantity' => $data['stock'],
    'unit_cost' => $data['unit_cost'],
    'sales_price' => $data['sale_price'],
    'stock' => $data['stock'],
    'movement_type' => 'ENTRY',
    'created_user_id' => $data['created_user_id'],
    'created_date' => $data['created_date'],
    'updated_user_id' => $data['updated_user_id'],
    'updated_date' => $data['updated_date']
  ];

  if (!$materialHistorical->create($historicalData)) {
    $db->rollBack();
    echo json_encode(array("message" => "error_creating_record"));
    exit;
  }

  $audit = [
    'created_user_id' => $data['created_user_id'],
    'created_date' => $data['created_date'],
    'updated_user_id' => $data['updated_user_id'],
    'updated_date' => $data['updated_date'],
  ];

  if (!material_historical_expense_sync_on_create($expense, $db, $material, $historicalData, $audit)) {
    $db->rollBack();
    echo json_encode(array("message" => "error_creating_record"));
    exit;
  }

  $db->commit();
  echo json_encode(['material' => []]);
} catch (Throwable $e) {
  if (isset($db) && $db->inTransaction()) {
    $db->rollBack();
  }
  http_response_code(401);
  die('EXPIRED');
}
