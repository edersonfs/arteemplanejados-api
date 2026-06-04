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
include_once '../../model/budget.php';
include_once '../../model/order.php';
include_once '../../utils/budget_order.php';

$conn = new Connection();
$db = $conn->connect();

try {
  $decoded = JWT::decode($token, new Key($_SERVER['KEY'], 'HS256'));

  $budget = new Budget($db);

  $oldRow = $budget->getById($_POST['id']);
  if (!$oldRow) {
    echo json_encode(array("message" => "record_does_not_exist"));
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

  if (array_key_exists('internal_client_id', $_POST)) {
    $internalClientId = $_POST['internal_client_id'] !== '' ? (int) $_POST['internal_client_id'] : null;
  } else {
    $rawIc = $oldRow['internal_client_id'] ?? null;
    $internalClientId = ($rawIc !== null && $rawIc !== '') ? (int) $rawIc : null;
  }

  $data = [
    'id' => $_POST['id'] ?? null,
    'company_id' => $companyId,
    'internal_client_id' => $internalClientId,
    'number' => $_POST['number'] ?? null,
    'status' => $_POST['status'] ?? null,
    'cost' => $_POST['cost'] ?? null,
    'sale' => $_POST['sale'] ?? null,
    'profit' => $_POST['profit'] ?? null,
    'image_file' => $image_file,
    'image_path' => $image_path,
    'updated_user_id' => $_POST['updated_user_id'] ?? null,
    'updated_date' => $_POST['updated_date'] ?? null
  ];

  if (!empty($data['number']) && $budget->existsByNumberWhenEdit($data['number'], $data['id'], $companyId)) {
    echo json_encode([
      "message" => "number_already_exists"
    ]);
    exit;
  }

  if ($budget->update($data)) {
    $order = new Order($db);
    $budgetRow = $budget->getById($data['id']);

    if (!budget_order_sync_if_approved($order, (int) $data['id'], $budgetRow)) {
      echo json_encode(array("message" => "error_creating_order"));
      exit;
    }

    echo json_encode(['budget' => []]);
  } else {
    echo json_encode(array("message" => "error_updating_record"));
  }
} catch (Throwable $e) {
  http_response_code(401);
  die('EXPIRED');
}
