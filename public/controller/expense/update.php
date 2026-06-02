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
include_once '../../model/expense.php';

$conn = new Connection();
$db = $conn->connect();

try {
    $decoded = JWT::decode($token, new Key($_SERVER['KEY'], 'HS256'));

    $expense = new Expense($db);

    $oldRow = $expense->getById($_POST['id']);
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
    $expenseTypeId = isset($_POST['expense_type_id']) ? (int) $_POST['expense_type_id'] : (int) $oldRow['expense_type_id'];

    $orderId = null;
    if (array_key_exists('order_id', $_POST)) {
        if ($_POST['order_id'] === '' || $_POST['order_id'] === null) {
            $orderId = null;
        } else {
            $orderId = (int) $_POST['order_id'];
        }
    } else {
        $oid = $oldRow['order_id'] ?? null;
        $orderId = ($oid === null || $oid === '') ? null : (int) $oid;
    }

    $supplierId = null;
    if (array_key_exists('supplier_id', $_POST)) {
        if ($_POST['supplier_id'] === '' || $_POST['supplier_id'] === null) {
            $supplierId = null;
        } else {
            $supplierId = (int) $_POST['supplier_id'];
        }
    } else {
        $sid = $oldRow['supplier_id'] ?? null;
        $supplierId = ($sid === null || $sid === '') ? null : (int) $sid;
    }

    $materialId = null;
    if (array_key_exists('material_id', $_POST)) {
        if ($_POST['material_id'] === '' || $_POST['material_id'] === null) {
            $materialId = null;
        } else {
            $materialId = (int) $_POST['material_id'];
        }
    } else {
        $mid = $oldRow['material_id'] ?? null;
        $materialId = ($mid === null || $mid === '') ? null : (int) $mid;
    }

    $orderItemId = null;
    if (array_key_exists('order_item_id', $_POST)) {
        if ($_POST['order_item_id'] === '' || $_POST['order_item_id'] === null) {
            $orderItemId = null;
        } else {
            $orderItemId = (int) $_POST['order_item_id'];
        }
    } else {
        $oiid = $oldRow['order_item_id'] ?? null;
        $orderItemId = ($oiid === null || $oiid === '') ? null : (int) $oiid;
    }

    $data = [
        'id' => $_POST['id'] ?? null,
        'company_id' => $companyId,
        'order_id' => $orderId,
        'order_item_id' => $orderItemId,
        'supplier_id' => $supplierId,
        'material_id' => $materialId,
        'expense_type_id' => $expenseTypeId,
        'description' => $_POST['description'] ?? null,
        'quantity' => $_POST['quantity'] ?? null,
        'value' => $_POST['value'] ?? null,
        'expense_date' => $_POST['expense_date'] ?? null,
        'payment_date' => $_POST['payment_date'] ?? null,
        'status' => $_POST['status'] ?? null,
        'image_file' => $image_file,
        'image_path' => $image_path,
        'updated_user_id' => $_POST['updated_user_id'] ?? null,
        'updated_date' => $_POST['updated_date'] ?? null
    ];

    if ($expense->update($data)) {
        echo json_encode(['expense' => []]);
    } else {
        echo json_encode(array("message" => "error_updating_record"));
    }
} catch (Throwable $e) {
    http_response_code(401);
    die('EXPIRED');
}

?>
