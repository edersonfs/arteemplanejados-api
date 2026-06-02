<?php

require '../../../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use app\database\Connection;

// start cors
if (isset($_SERVER['HTTP_ORIGIN'])) {
  header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
  header('Access-Control-Allow-Credentials: true');
  header('Access-Control-Max-Age: 86400');    // cache for 1 day
}

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
    header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

  exit(0);
}

// required headers
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: HEAD, GET, POST, PUT, PATCH, DELETE, OPTIONS");
header('Access-Control-Allow-Headers: Authorization, Content-Type, x-xsrf-token, x_csrftoken, Cache-Control, X-Requested-With');

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__, 4));
$dotenv->load();

$authorization = $_SERVER['HTTP_AUTHORIZATION'];
$token = str_replace('Bearer ', '', $authorization);

// include database and object files
include_once '../../../app/database/Connection.php';
include_once '../../model/contact.php';

// instantiate database and object
$conn = new Connection();
$db = $conn->connect();

try {
  $decoded = JWT::decode($token, new Key($_SERVER['KEY'], 'HS256'));

  // initialize object
  $contact = new Contact($db);

  if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = dirname(__FILE__, 3) . '/wwwroot/images/';

    if (!file_exists($uploadDir)) {
      mkdir($uploadDir, 0777, true);
    }

    $fileName = basename($_FILES['image_file']['name']);
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

  $data = [
    'title' => $_POST['title'] ?? null,
    'button' => $_POST['button'] ?? null,
    'image_file' => $image_file,
    'image_path' => $image_path,
    'address' => $_POST['address'] ?? null,
    'contact_01' => $_POST['contact_01'] ?? null,
    'contact_02' => $_POST['contact_02'] ?? null,
    'email' => $_POST['email'] ?? null,
    'instagram' => $_POST['instagram'] ?? null,
    'youtube' => $_POST['youtube'] ?? null,
    'site' => $_POST['site'] ?? null,
    'created_user_id' => $_POST['created_user_id'] ?? null,
    'created_date' => $_POST['created_date'] ?? null,
    'updated_user_id' => $_POST['updated_user_id'] ?? null,
    'updated_date' => $_POST['updated_date'] ?? null
  ];

  if ($contact->existsByTitle($data['title'])) {
    echo json_encode([
      "message" => "record_already_exists"
    ]);
    exit;
  }

  if ($contact->create($data)) {
    echo json_encode(['contact' => []]);
  } else {
    echo json_encode(array("message" => "error_creating_record"));
  }
} catch (Throwable $e) {
  http_response_code(401);
  die('EXPIRED');
}
