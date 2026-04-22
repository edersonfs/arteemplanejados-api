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
include_once '../../model/about_us.php';
include_once '../../utils/utils.php';
 
// instantiate database and object
$conn = new Connection();
$db = $conn->connect();

try {        
    // $decoded = JWT::decode($token, new Key($_SERVER['KEY'], 'HS256'));

    // initialize object
    $aboutUs = new AboutUs($db);  
    $utils = new Utils();  
    
    // Get PUT data
    $data = $_POST;
    $files = $_FILES;

    $oldAboutUs = $aboutUs->getById($_POST['id']);
    
    // Handle top image upload if provided
    $image_file = $oldAboutUs['image_file'];
    $image_path = $oldAboutUs['image_path'];
    
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
    $image_file_02 = $oldAboutUs['image_file_02'];
    $image_path_02 = $oldAboutUs['image_path_02'];;
    
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

    // Prepare data for update
    $data = [
        'id' => $_POST['id'] ?? null,
        'title' => $_POST['title'] ?? null,
        'sub_title' => $_POST['sub_title'] ?? null,
        'content' => $_POST['content'] ?? null,
        'video' => $_POST['video'] ?? null,
        'image_file' => $image_file,
        'image_path' => $image_path,  
        'image_file_02' => $image_file_02,
        'image_path_02' => $image_path_02,  
        'updated_user_id' => $_POST['updated_user_id'] ?? null,
        'updated_date' => date('Y-m-d H:i:s')
    ];        

    // Check if record already exists
    if ($aboutUs->existsByTitleWhenEdit($data['title'], $data['id'])) {
      echo json_encode([
          "message" => "record_already_exists"
      ]);
      exit;
    } 

    // Update the record    
    if ($aboutUs->update($data)) {        
        echo json_encode(['about_us' => []]);
    } else {
        echo json_encode(array("message" => "error_updating_record"));
    }    
} catch (Throwable $e) {
    http_response_code(401);
    die('EXPIRED');
}
?> 