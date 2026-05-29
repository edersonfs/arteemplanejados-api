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
include_once '../../model/material.php';
include_once '../../model/material_historical.php';

$conn = new Connection();
$db = $conn->connect();

try {
    $decoded = JWT::decode($token, new Key($_SERVER['KEY'], 'HS256'));

    $material = new Material($db);
    $materialHistorical = new MaterialHistorical($db);


    $oldRow = $material->getById($_POST['id']);
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
    $supplierId = isset($_POST['supplier_id']) ? (int) $_POST['supplier_id'] : (int) $oldRow['supplier_id'];

    $data = [
        'id' => $_POST['id'] ?? null,
        'company_id' => $companyId,
        'supplier_id' => $supplierId,
        'name' => $_POST['name'] ?? null,
        'description' => $_POST['description'] ?? null,
        'unit_cost' => $_POST['unit_cost'] ?? null,
        'sale_price' => $_POST['sale_price'] ?? null,
        'stock' => $_POST['stock'] ?? null,
        'image_file' => $image_file,
        'image_path' => $image_path,
        'updated_user_id' => $_POST['updated_user_id'] ?? null,
        'updated_date' => $_POST['updated_date'] ?? null
    ];

    $materialId = (int) $data['id'];

    $db->beginTransaction();

    if (!$material->update($data)) {
        $db->rollBack();
        echo json_encode(array("message" => "error_updating_record"));
        exit;
    }

    $snapshotRow = $materialHistorical->findSnapshotRowForMaterialSync($materialId);

    $historicalPayload = [
        'material_id' => $materialId,
        'company_id' => $companyId,
        'supplier_id' => $supplierId,
        'explanation' => $data['description'],
        'quantity' => $data['stock'],
        'unit_cost' => $data['unit_cost'],
        'sales_price' => $data['sale_price'],
        'stock' => $data['stock'],
        'updated_user_id' => $data['updated_user_id'],
        'updated_date' => $data['updated_date'],
    ];

    if ($snapshotRow) {
        $mhFull = $materialHistorical->getById((int) $snapshotRow['id']);
        $historicalPayload['id'] = (int) $snapshotRow['id'];
        $oid = $snapshotRow['order_item_id'] ?? null;
        $historicalPayload['order_item_id'] = ($oid === null || $oid === '')
            ? null
            : (int) $oid;
        $historicalPayload['movement_type'] = 'ADJUSTMENT';
        $historicalOk = $materialHistorical->update($historicalPayload);
    } else {
        $historicalPayload['order_item_id'] = null;
        $historicalPayload['movement_type'] = 'ENTRY';
        $historicalPayload['created_user_id'] = $data['updated_user_id'];
        $historicalPayload['created_date'] = $data['updated_date'];
        $historicalOk = $materialHistorical->create($historicalPayload);
    }

    if (!$historicalOk) {
        $db->rollBack();
        echo json_encode(array("message" => "error_updating_record"));
        exit;
    }

    $db->commit();
    echo json_encode(['material' => []]);
} catch (Throwable $e) {
    if (isset($db) && $db->inTransaction()) {
        $db->rollBack();
    }
    http_response_code(401);
    die('EXPIRED' . $e);
}

?>
