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
include_once '../../model/income.php';

$conn = new Connection();
$db = $conn->connect();

try {
    $decoded = JWT::decode($token, new Key($_SERVER['KEY'], 'HS256'));

    $income = new Income($db);

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

    $internalClientId = null;
    if (isset($_POST['internal_client_id']) && $_POST['internal_client_id'] !== '') {
        $internalClientId = (int) $_POST['internal_client_id'];
    }

    $orderId = null;
    if (isset($_POST['order_id']) && $_POST['order_id'] !== '') {
        $orderId = (int) $_POST['order_id'];
    }

    $data = [
        'company_id' => $companyId,
        'internal_client_id' => $internalClientId,
        'order_id' => $orderId,
        'amount' => $_POST['amount'] ?? null,
        'due_date' => $_POST['due_date'] ?? null,
        'payment_date' => $_POST['payment_date'] ?? null,
        'payment_method' => $_POST['payment_method'] ?? null,
        'status' => $_POST['status'] ?? null,
        'image_file' => $image_file,
        'image_path' => $image_path,
        'created_user_id' => $_POST['created_user_id'] ?? null,
        'created_date' => $_POST['created_date'] ?? null,
        'updated_user_id' => $_POST['updated_user_id'] ?? null,
        'updated_date' => $_POST['updated_date'] ?? null
    ];

    if ($income->create($data)) {
        echo json_encode(['income' => []]);
    } else {
        echo json_encode(array("message" => "error_creating_record"));
    }
} catch (Throwable $e) {
    http_response_code(401);
    die('EXPIRED');
}

?>
