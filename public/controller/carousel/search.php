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
include_once '../../model/carousel.php';
include_once '../../utils/utils.php';

// instantiate database and object
$conn = new Connection();
$db = $conn->connect();

try {
  $decoded = JWT::decode($token, new Key($_SERVER['KEY'], 'HS256'));

  // Get search term parameter
  $searchTerm = isset($_GET['search_term']) ? $_GET['search_term'] : '';

  // Initialize object
  $carousel = new Carousel($db);
  $utils = new Utils();

  // parameters
  $search = filter_input(INPUT_GET, 'search', FILTER_DEFAULT);
  $search = "%$search%"; // Add wildcards for LIKE search

  $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
  $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 9999;
  $offset = ($page - 1) * $limit;

  $stmt_pag = $carousel->search($search, $limit, $offset);
  $total = is_array($stmt_pag) ? count($stmt_pag) : $stmt_pag->rowCount();

  if (is_array($stmt_pag)) {
    $num = count($stmt_pag);
  } else {
    $num = $stmt_pag->rowCount();
  }

  // check if more than 0 record found
  if ($total > 0) {
    echo json_encode([
      'carousel' => $stmt_pag,
      "total" => $total,
      "page" => $page,
      "totalPages" => ceil($total / $limit),
      "limit" => $limit
    ]);
  } else {
    echo json_encode(
      array("message" => "record_does_not_exist")
    );
  }
} catch (Throwable $e) {
  http_response_code(401);
  die('EXPIRED');
}
