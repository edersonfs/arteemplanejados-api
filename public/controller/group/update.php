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
    header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");

  if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
    header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

  exit(0);
}

// end cors

// required header
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
include_once '../../model/group.php';

// instantiate database and object
$conn = new Connection();
$db = $conn->connect();

try {
  $decoded = JWT::decode($token, new Key($_SERVER['KEY'], 'HS256'));

  $group_id = isset($decoded->group_id) ? (int) $decoded->group_id : null;

  if ($group_id != 1) {
    echo json_encode([
      "message" => "permission_denied"
    ]);
    exit;
  }

  // initialize object
  $group = new Group($db);

  // Get ID parameter from PUT data
  $putData = file_get_contents("php://input");
  $data = json_decode($putData, true);

  $id = $data['id'] ?? null;

  if (!$id) {
    http_response_code(400);
    die('ID parameter is required');
  }

  // Check if record already exists
  if ($group->existsByNameWhenEdit($data['name'], $data['id'])) {
    echo json_encode([
      "message" => "record_already_exists"
    ]);
    exit;
  }

  // Update record without image
  if ($group->update($data)) {
    echo json_encode(['group' => []]);
  } else {
    echo json_encode(['message' => 'error_updating_record']);
  }
} catch (Throwable $e) {
  http_response_code(401);
  die('EXPIRED');
}
