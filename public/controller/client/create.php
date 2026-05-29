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
include_once '../../model/client.php';

$conn = new Connection();
$db = $conn->connect();

try {
    $decoded = JWT::decode($token, new Key($_SERVER['KEY'], 'HS256'));

    $client = new Client($db);

    $uploadDir = dirname(__FILE__, 3) . '/wwwroot/images/';
    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $imageData = [];
    foreach (Client::imageFieldPairs() as $pair) {
        $fn = $pair['file'];
        if (isset($_FILES[$fn]) && $_FILES[$fn]['error'] === UPLOAD_ERR_OK) {
            $extension = pathinfo($_FILES[$fn]['name'], PATHINFO_EXTENSION);
            $fileName = uniqid('img_', true) . '.' . $extension;
            $targetPath = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES[$fn]['tmp_name'], $targetPath)) {
                $imageData[$pair['file']] = $fileName;
                $imageData[$pair['path']] = 'wwwroot/images/' . $fileName;
            } else {
                http_response_code(500);
                die('Error uploading file');
            }
        } else {
            $imageData[$pair['file']] = null;
            $imageData[$pair['path']] = null;
        }
    }

    $data = array_merge([
        'name' => $_POST['name'] ?? null,
        'date' => $_POST['date'] ?? null,
        'address' => $_POST['address'] ?? null,
        'phone' => $_POST['phone'] ?? null,
        'email' => $_POST['email'] ?? null,
        'active' => $_POST['active'] == 'true' ? 1 : 0 ?? null,      
        'city' => $_POST['city'] ?? null,
        'state' => $_POST['state'] ?? null,
        'description' => $_POST['description'] ?? null,
        'video' => $_POST['video'] ?? null,
        'created_user_id' => $_POST['created_user_id'] ?? null,
        'created_date' => $_POST['created_date'] ?? null,
        'updated_user_id' => $_POST['updated_user_id'] ?? null,
        'updated_date' => $_POST['updated_date'] ?? null,
    ], $imageData);

    if (!empty($data['email']) && $client->existsByEmail($data['email'])) {
        echo json_encode([
            "message" => "record_already_exists"
        ]);
        exit;
    }

    if ($client->create($data)) {
        echo json_encode(['client' => []]);
    } else {
        echo json_encode(array("message" => "error_creating_record"));
    }
} catch (Throwable $e) {
    http_response_code(401);
    die('EXPIRED' . $e);
}
?>
