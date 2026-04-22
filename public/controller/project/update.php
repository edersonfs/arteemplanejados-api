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
include_once '../../model/project.php';
 
// instantiate database and object
$conn = new Connection();
$db = $conn->connect();

try {        
    $decoded = JWT::decode($token, new Key($_SERVER['KEY'], 'HS256'));

    // initialize object
    $project = new Project($db);  
    
    // Check if ID exists in the request
    if (!isset($_REQUEST['id'])) {      
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (isset($data['id'])) {
            $id = $data['id'];
        } else {
            http_response_code(400);
            die('ID is required for update');
        }
    } else {
        $id = $_REQUEST['id'];
    }

    // Take old images
    $oldProject = $project->getById($id);    
    
    // Handle top image upload if provided
    $image_file = $oldProject['image_file'];
    $image_path = $oldProject['image_path'];
    
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = dirname(__FILE__, 3) . '/wwwroot/images/';
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = basename($_FILES['image_file']['name']);
        $targetPath = $uploadDir . $fileName;      
        
        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $targetPath)) {
            $image_file = $fileName;
            $image_path = '/wwwroot/images/' . $fileName;
        } else {
            http_response_code(500);
            die('Error uploading image');
        }
    }

    // Handle description image upload if provided
    $image_file_02 = $oldProject['image_file_02'];
    $image_path_02 = $oldProject['image_path_02'];
    
    if (isset($_FILES['image_file_02']) && $_FILES['image_file_02']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = dirname(__FILE__, 3) . '/wwwroot/images/';
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName02 = basename($_FILES['image_file_02']['name']);
        $targetPath02 = $uploadDir . $fileName02;      
        
        if (move_uploaded_file($_FILES['image_file_02']['tmp_name'], $targetPath02)) {
            $image_file_02 = $fileName02;
            $image_path_02 = '/wwwroot/images/' . $fileName02;
        } else {
            http_response_code(500);
            die('Error uploading 02 image');
        }
    }

    // Handle description image upload if provided
    $image_file_03 = $oldProject['image_file_03'];;
    $image_path_03 = $oldProject['image_path_03'];
    
    if (isset($_FILES['image_file_03']) && $_FILES['image_file_03']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = dirname(__FILE__, 3) . '/wwwroot/images/';
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName03 = basename($_FILES['image_file_03']['name']);
        $targetPath03 = $uploadDir . $fileName03;      
        
        if (move_uploaded_file($_FILES['image_file_03']['tmp_name'], $targetPath03)) {
            $image_file_03 = $fileName03;
            $image_path_03 = '/wwwroot/images/' . $fileName03;
        } else {
            http_response_code(500);
            die('Error uploading 03 image');
        }
    }

    // Handle description image upload if provided
    $image_file_04 = $oldProject['image_file_04'];;
    $image_path_04 = $oldProject['image_path_04'];
    
    if (isset($_FILES['image_file_04']) && $_FILES['image_file_04']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = dirname(__FILE__, 3) . '/wwwroot/images/';
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName04 = basename($_FILES['image_file_04']['name']);
        $targetPath04 = $uploadDir . $fileName04;
        
        if (move_uploaded_file($_FILES['image_file_04']['tmp_name'], $targetPath04)) {
            $image_file_04 = $fileName04;
            $image_path_04 = '/wwwroot/images/' . $fileName04;
        } else {
            http_response_code(500);
            die('Error uploading 04 image');
        }
    }

    // Prepare data for update
    $data = [
        'id' => $id,
        'name' => $_REQUEST['name'] ?? null,
        'description' => $_REQUEST['description'] ?? null,
        'description_internal' => $_REQUEST['description_internal'] ?? null,
        'start' => $_REQUEST['start'] ?? null,
        'active' => $_REQUEST['active'] ?? null,
        'contact' => $_REQUEST['contact'] ?? null,
        'name_responsible' => $_REQUEST['name_responsible'] ?? null,
        'position' => $_REQUEST['position'] ?? null,
        'video' => $_REQUEST['video'] ?? null,
        'image_file' => $image_file,
        'image_path' => $image_path,
        'image_file_02' => $image_file_02,
        'image_path_02' => $image_path_02,
        'image_file_03' => $image_file_03,
        'image_path_03' => $image_path_03,
        'image_file_04' => $image_file_04,
        'image_path_04' => $image_path_04,
        'updated_user_id' => $_REQUEST['updated_user_id'] ?? null,
        'updated_date' => date('Y-m-d H:i:s')
    ];  
    
    // Check if record already exists
    if ($project->existsByNameWhenEdit($data['name'], $data['id'])) {
      echo json_encode([
          "message" => "record_already_exists"
      ]);
      exit;
    } 

    // Update the record    
    if ($project->update($data)) {        
        echo json_encode(['project' => []]);
    } else {
        echo json_encode(array("message" => "error_updating_record"));
    }
    
} catch (Throwable $e) {
    http_response_code(401);
    die('EXPIRED');
}
?> 