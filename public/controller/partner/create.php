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
include_once '../../model/partner.php';

$conn = new Connection();
$db = $conn->connect();

try {
    $decoded = JWT::decode($token, new Key($_SERVER['KEY'], 'HS256'));

    $partner = new Partner($db);

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
        'name' => $_POST['name'] ?? null,
        'image_file' => $image_file,
        'image_path' => $image_path,
        'created_user_id' => $_POST['created_user_id'] ?? null,
        'created_date' => $_POST['created_date'] ?? null,
        'updated_user_id' => $_POST['updated_user_id'] ?? null,
        'updated_date' => $_POST['updated_date'] ?? null
    ];

    if ($partner->existsByName($data['name'])) {
        echo json_encode([
            "message" => "record_already_exists"
        ]);
        exit;
    }

    if ($partner->create($data)) {
        echo json_encode(['partner' => []]);
    } else {
        echo json_encode(array("message" => "error_creating_record"));
    }
} catch (Throwable $e) {
    http_response_code(401);
    die('EXPIRED' . $e);
}
?>
