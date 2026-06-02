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
include_once '../../model/budget_item.php';

$conn = new Connection();
$db = $conn->connect();

try {
    $decoded = JWT::decode($token, new Key($_SERVER['KEY'], 'HS256'));

    $budgetItem = new BudgetItem($db);

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

    $budgetId = isset($_POST['budget_id']) ? (int) $_POST['budget_id'] : null;

    if (empty($budgetId)) {
        echo json_encode(array("message" => "missing_budget_id"));
        exit;
    }

    $materialId = null;
    if (isset($_POST['material_id']) && $_POST['material_id'] !== '') {
        $materialId = (int) $_POST['material_id'];
    }

    $hours = null;
    if (isset($_POST['hours']) && $_POST['hours'] !== '') {
        $hours = (int) $_POST['hours'];
    }

    $fixedCost = null;
    if (isset($_POST['fixed_cost']) && $_POST['fixed_cost'] !== '') {
        $fixedCost = (int) $_POST['fixed_cost'];
    }

    $freight = null;
    if (isset($_POST['freight']) && $_POST['freight'] !== '') {
        $freight = $_POST['freight'];
    }

    $data = [
        'budget_id' => $budgetId,
        'budget_item_type' => $_POST['budget_item_type'] ?? null,
        'material_id' => $materialId,
        'hours' => $hours,
        'fixed_cost' => $fixedCost,
        'freight' => $freight,
        'description' => $_POST['description'] ?? null,
        'quantity' => $_POST['quantity'] ?? null,
        'width' => $_POST['width'] ?? null,
        'height' => $_POST['height'] ?? null,
        'unit_price' => $_POST['unit_price'] ?? null,
        'total' => $_POST['total'] ?? null,
        'image_file' => $image_file,
        'image_path' => $image_path,
        'created_user_id' => $_POST['created_user_id'] ?? null,
        'created_date' => $_POST['created_date'] ?? null,
        'updated_user_id' => $_POST['updated_user_id'] ?? null,
        'updated_date' => $_POST['updated_date'] ?? null
    ];

    if ($budgetItem->create($data)) {
        echo json_encode(['budget_item' => []]);
    } else {
        echo json_encode(array("message" => "error_creating_record"));
    }
} catch (Throwable $e) {
    http_response_code(401);
    die('EXPIRED');
}

?>
