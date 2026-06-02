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
include_once '../../model/blog.php';

$conn = new Connection();
$db = $conn->connect();

try {
    $decoded = JWT::decode($token, new Key($_SERVER['KEY'], 'HS256'));

    $blog = new Blog($db);

    $uploadDir = dirname(__FILE__, 3) . '/wwwroot/images/';

    if (!file_exists($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $image_file = $image_path = null;

    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {        
        $extension = pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('img_', true) . '.' . $extension;
        $targetPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $targetPath)) {
            $image_file = $fileName;
            $image_path = 'wwwroot/images/' . $fileName;
        } else {
            http_response_code(500);
            die('Error uploading image');
        }
    }

    $image_file_02 = $image_path_02 = null;
    if (isset($_FILES['image_file_02']) && $_FILES['image_file_02']['error'] === UPLOAD_ERR_OK) {
        $extension = pathinfo($_FILES['image_file_02']['name'], PATHINFO_EXTENSION);
        $fileName02 = uniqid('img_', true) . '.' . $extension;
        $targetPath02 = $uploadDir . $fileName02;

        if (move_uploaded_file($_FILES['image_file_02']['tmp_name'], $targetPath02)) {
            $image_file_02 = $fileName02;
            $image_path_02 = 'wwwroot/images/' . $fileName02;
        } else {
            http_response_code(500);
            die('Error uploading 02 image');
        }
    }

    $image_file_03 = $image_path_03 = null;
    if (isset($_FILES['image_file_03']) && $_FILES['image_file_03']['error'] === UPLOAD_ERR_OK) {
        $extension = pathinfo($_FILES['image_file_03']['name'], PATHINFO_EXTENSION);
        $fileName03 = uniqid('img_', true) . '.' . $extension;
        $targetPath03 = $uploadDir . $fileName03;
        if (move_uploaded_file($_FILES['image_file_03']['tmp_name'], $targetPath03)) {
            $image_file_03 = $fileName03;
            $image_path_03 = 'wwwroot/images/' . $fileName03;
        } else {
            http_response_code(500);
            die('Error uploading 03 image');
        }
    }

    $image_file_04 = $image_path_04 = null;
    if (isset($_FILES['image_file_04']) && $_FILES['image_file_04']['error'] === UPLOAD_ERR_OK) {
        $extension = pathinfo($_FILES['image_file_04']['name'], PATHINFO_EXTENSION);
        $fileName04 = uniqid('img_', true) . '.' . $extension;
        $targetPath04 = $uploadDir . $fileName04;
        
        if (move_uploaded_file($_FILES['image_file_04']['tmp_name'], $targetPath04)) {
            $image_file_04 = $fileName04;
            $image_path_04 = 'wwwroot/images/' . $fileName04;
        } else {
            http_response_code(500);
            die('Error uploading 04 image');
        }
    }

    $data = [
        'title' => $_POST['title'] ?? null,
        'date' => $_POST['date'] ?? null,
        'description' => $_POST['description'] ?? null,
        'text' => $_POST['text'] ?? null,
        'text_02' => $_POST['text_02'] ?? null,
        'category' => $_POST['category'] ?? null,
        'redactor' => $_POST['redactor'] ?? null,
        'video' => $_POST['video'] ?? null,
        'image_file' => $image_file,
        'image_path' => $image_path,
        'image_file_02' => $image_file_02,
        'image_path_02' => $image_path_02,
        'image_file_03' => $image_file_03,
        'image_path_03' => $image_path_03,
        'image_file_04' => $image_file_04,
        'image_path_04' => $image_path_04,
        'created_user_id' => $_POST['created_user_id'] ?? null,
        'created_date' => $_POST['created_date'] ?? null,
        'updated_user_id' => $_POST['updated_user_id'] ?? null,
        'updated_date' => $_POST['updated_date'] ?? null
    ];

    if ($blog->existsByTitle($data['title'])) {
        echo json_encode([
            "message" => "record_already_exists"
        ]);
        exit;
    }

    if ($blog->create($data)) {
        echo json_encode(['blog' => []]);
    } else {
        echo json_encode(array("message" => "error_creating_record"));
    }
} catch (Throwable $e) {
    http_response_code(401);
    die('EXPIRED' . $e);
}
?>
