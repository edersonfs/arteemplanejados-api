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
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

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
include_once '../../utils/utils.php';

$conn = new Connection();
$db = $conn->connect();

try {
    $decoded = JWT::decode($token, new Key($_SERVER['KEY'], 'HS256'));

    $group_id = isset($decoded->group_id) ? (int) $decoded->group_id : null;
    $company_id = isset($decoded->company_id) ? (int) $decoded->company_id : null;

    $material = new Material($db);
    $utils = new Utils();

    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 9999;
    $offset = ($page - 1) * $limit;

    $stmt = $material->getAll($company_id);
    $stmt_pag = $material->getPagination($limit, $offset, $company_id);
    $total = is_array($stmt) ? count($stmt) : $stmt->rowCount();

    if (is_array($stmt)) {
        $num = count($stmt);
    } else {
        $num = $stmt->rowCount();
    }

    $stmt = $utils->utf8ize($stmt);

    if ($num > 0) {
        echo json_encode([
            'material' => $stmt_pag,
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

?>
