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

// required header
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
include_once '../../model/collaborator.php';
include_once '../../utils/utils.php';
 
// instantiate database and category object
$conn = new Connection();
$db = $conn->connect();

try {        
    $decoded = JWT::decode($token, new Key($_SERVER['KEY'], 'HS256'));

    // initialize object
    $collaborator = new Collaborator($db);  
    $utils = new Utils(); 

    // Get PUT data
    $data = $_POST;
    $files = $_FILES;

    $oldCollaborator = $collaborator->getById($_POST['id']);
    
    // Handle top image upload if provided
    $image_file = $oldCollaborator['image_file'];
    $image_path = $oldCollaborator['image_path'];
    
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = dirname(__FILE__, 3) . '/wwwroot/images/';
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName = basename($_FILES['image_file']['name']) . '_' . date('Ymd_His');
        $targetPath = $uploadDir . $fileName;    
        
        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $targetPath)) {
            $image_file = $fileName;
            $image_path = '/wwwroot/images/' . $fileName;
        } else {
            http_response_code(500);
            die('Error uploading image');
        }
    }

    // Prepare data for update - use $_REQUEST instead of $_POST
    $data = [
        'id' => $_POST['id'] ?? null,
        'name' => $_REQUEST['name'] ?? null,
        'position' => $_REQUEST['position'] ?? null,
        'description' => $_REQUEST['description'] ?? null,     
        'image_file' => $image_file,
        'image_path' => $image_path, 
        'order' => $_REQUEST['order'] ?? null,
        'facebook' => $_REQUEST['facebook'] ?? null,
        'instagram' => $_REQUEST['instagram'] ?? null,
        'linkedin' => $_REQUEST['linkedin'] ?? null,
        'updated_user_id' => $_REQUEST['updated_user_id'] ?? null,
        'updated_date' => date('Y-m-d H:i:s')
    ];  

    // Check if record already exists
    if ($collaborator->existsByNameWhenEdit($data['name'], $data['id'])) {
      echo json_encode([
          "message" => "record_already_exists"
      ]);
      exit;
    } 

    // Update the record    
    if ($collaborator->update($data)) {        
        echo json_encode(['collaborator' => []]);
    } else {
        echo json_encode(array("message" => "error_updating_record"));
    }       
} catch (Throwable $e) {
    http_response_code(401);
    die('EXPIRED' . $e);
}

?>