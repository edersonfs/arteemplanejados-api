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
include_once '../../model/event.php';
 
// instantiate database and object
$conn = new Connection();
$db = $conn->connect();

try {        
    // $decoded = JWT::decode($token, new Key($_SERVER['KEY'], 'HS256'));

    // initialize object
    $event = new Event($db);  
    
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
    $oldEvent = $event->getById($id);
    
    // Handle top image upload if provided
    $image_file = $oldEvent['image_file'];
    $image_path = $oldEvent['image_path'];;
    
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
    $image_file_02 = $oldEvent['image_file_02'];
    $image_path_02 = $oldEvent['image_path_02'];;
    
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

    // Handle 03 image upload
    $image_file_03 = $oldEvent['image_file_03'];
    $image_path_03 = $oldEvent['image_path_03'];
    
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

    // Handle 04 image upload
    $image_file_04 = $oldEvent['image_file_04'];
    $image_path_04 = $oldEvent['image_path_04'];

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

    // Handle 05 image upload
    $image_file_05 = $oldEvent['image_file_05'];
    $image_path_05 = $oldEvent['image_path_05'];

    if (isset($_FILES['image_file_05']) && $_FILES['image_file_05']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = dirname(__FILE__, 3) . '/wwwroot/images/';
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName05 = basename($_FILES['image_file_05']['name']);
        $targetPath05 = $uploadDir . $fileName05;
        
        if (move_uploaded_file($_FILES['image_file_05']['tmp_name'], $targetPath05)) {
            $image_file_05 = $fileName05;
            $image_path_05 = '/wwwroot/images/' . $fileName05;
        } else {
            http_response_code(500);
            die('Error uploading 05 image');
        }
    }

    // Handle 06 image upload
    $image_file_06 = $oldEvent['image_file_06'];
    $image_path_06 = $oldEvent['image_path_06'];

    if (isset($_FILES['image_file_06']) && $_FILES['image_file_06']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = dirname(__FILE__, 3) . '/wwwroot/images/';
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName06 = basename($_FILES['image_file_06']['name']);
        $targetPath06 = $uploadDir . $fileName06;
        
        if (move_uploaded_file($_FILES['image_file_06']['tmp_name'], $targetPath06)) {
            $image_file_06 = $fileName06;
            $image_path_06 = '/wwwroot/images/' . $fileName06;
        } else {
            http_response_code(500);
            die('Error uploading 06 image');
        }
    }

    // Handle 07 image upload
    $image_file_07 = $oldEvent['image_file_07'];
    $image_path_07 = $oldEvent['image_path_07'];

    if (isset($_FILES['image_file_07']) && $_FILES['image_file_07']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = dirname(__FILE__, 3) . '/wwwroot/images/';
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName07 = basename($_FILES['image_file_07']['name']);
        $targetPath07 = $uploadDir . $fileName07;
        
        if (move_uploaded_file($_FILES['image_file_07']['tmp_name'], $targetPath07)) {
            $image_file_07 = $fileName07;
            $image_path_07 = '/wwwroot/images/' . $fileName07;
        } else {
            http_response_code(500);
            die('Error uploading 07 image');
        }
    }

    // Handle 08 image upload
    $image_file_08 = $oldEvent['image_file_08'];
    $image_path_08 = $oldEvent['image_path_08'];

    if (isset($_FILES['image_file_08']) && $_FILES['image_file_08']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = dirname(__FILE__, 3) . '/wwwroot/images/';
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName08 = basename($_FILES['image_file_08']['name']);
        $targetPath08 = $uploadDir . $fileName08;
        
        if (move_uploaded_file($_FILES['image_file_08']['tmp_name'], $targetPath08)) {
            $image_file_08 = $fileName08;
            $image_path_08 = '/wwwroot/images/' . $fileName08;
        } else {
            http_response_code(500);
            die('Error uploading 08 image');
        }
    }

    // Handle 09 image upload
    $image_file_09 = $oldEvent['image_file_09'];
    $image_path_09 = $oldEvent['image_path_09'];

    if (isset($_FILES['image_file_09']) && $_FILES['image_file_09']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = dirname(__FILE__, 3) . '/wwwroot/images/';
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName09 = basename($_FILES['image_file_09']['name']);
        $targetPath09 = $uploadDir . $fileName09;
        
        if (move_uploaded_file($_FILES['image_file_09']['tmp_name'], $targetPath09)) {
            $image_file_09 = $fileName09;
            $image_path_09 = '/wwwroot/images/' . $fileName09;
        } else {
            http_response_code(500);
            die('Error uploading 09 image');
        }
    }

    // Handle 10 image upload
    $image_file_10 = $oldEvent['image_file_10'];
    $image_path_10 = $oldEvent['image_path_10'];

    if (isset($_FILES['image_file_10']) && $_FILES['image_file_10']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = dirname(__FILE__, 3) . '/wwwroot/images/';
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName10 = basename($_FILES['image_file_10']['name']);
        $targetPath10 = $uploadDir . $fileName10;
        
        if (move_uploaded_file($_FILES['image_file_10']['tmp_name'], $targetPath10)) {
            $image_file_10 = $fileName10;
            $image_path_10 = '/wwwroot/images/' . $fileName10;
        } else {
            http_response_code(500);
            die('Error uploading 10 image');
        }
    }

    // Handle 11 image upload
    $image_file_11 = $oldEvent['image_file_11'];
    $image_path_11 = $oldEvent['image_path_11'];

    if (isset($_FILES['image_file_11']) && $_FILES['image_file_11']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = dirname(__FILE__, 3) . '/wwwroot/images/';
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName11 = basename($_FILES['image_file_11']['name']);
        $targetPath11 = $uploadDir . $fileName11;
        
        if (move_uploaded_file($_FILES['image_file_11']['tmp_name'], $targetPath11)) {
            $image_file_11 = $fileName11;
            $image_path_11 = '/wwwroot/images/' . $fileName11;
        } else {
            http_response_code(500);
            die('Error uploading 11 image');
        }
    }

    // Handle 12 image upload
    $image_file_12 = $oldEvent['image_file_12'];
    $image_path_12 = $oldEvent['image_path_12'];

    if (isset($_FILES['image_file_12']) && $_FILES['image_file_12']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = dirname(__FILE__, 3) . '/wwwroot/images/';
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName12 = basename($_FILES['image_file_12']['name']);
        $targetPath12 = $uploadDir . $fileName12;
        
        if (move_uploaded_file($_FILES['image_file_12']['tmp_name'], $targetPath12)) {
            $image_file_12 = $fileName12;
            $image_path_12 = '/wwwroot/images/' . $fileName12;
        } else {
            http_response_code(500);
            die('Error uploading 12 image');
        }
    }

    // Handle 13 image upload
    $image_file_13 = $oldEvent['image_file_13'];
    $image_path_13 = $oldEvent['image_path_13'];    

    if (isset($_FILES['image_file_13']) && $_FILES['image_file_13']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = dirname(__FILE__, 3) . '/wwwroot/images/';
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName13 = basename($_FILES['image_file_13']['name']);
        $targetPath13 = $uploadDir . $fileName13;
        
        if (move_uploaded_file($_FILES['image_file_13']['tmp_name'], $targetPath13)) {
            $image_file_13 = $fileName13;
            $image_path_13 = '/wwwroot/images/' . $fileName13;
        } else {
            http_response_code(500);
            die('Error uploading 13 image');
        }
    }

    // Handle 14 image upload
    $image_file_14 = $oldEvent['image_file_14'];
    $image_path_14 = $oldEvent['image_path_14'];

    if (isset($_FILES['image_file_14']) && $_FILES['image_file_14']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = dirname(__FILE__, 3) . '/wwwroot/images/';
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName14 = basename($_FILES['image_file_14']['name']);
        $targetPath14 = $uploadDir . $fileName14;
        
        if (move_uploaded_file($_FILES['image_file_14']['tmp_name'], $targetPath14)) {
            $image_file_14 = $fileName14;
            $image_path_14 = '/wwwroot/images/' . $fileName14;
        } else {
            http_response_code(500);
            die('Error uploading 14 image');
        }
    }

    // Handle 15 image upload
    $image_file_15 = $oldEvent['image_file_15'];
    $image_path_15 = $oldEvent['image_path_15'];

    if (isset($_FILES['image_file_15']) && $_FILES['image_file_15']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = dirname(__FILE__, 3) . '/wwwroot/images/';
        
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $fileName15 = basename($_FILES['image_file_15']['name']);
        $targetPath15 = $uploadDir . $fileName15;
        
        if (move_uploaded_file($_FILES['image_file_15']['tmp_name'], $targetPath15)) {
            $image_file_15 = $fileName15;
            $image_path_15 = '/wwwroot/images/' . $fileName15;
        } else {
            http_response_code(500);
            die('Error uploading 15 image');
        }
    }

    // Prepare data for update
    $data = [
        'id' => $id,
        'name' => $_REQUEST['name'] ?? null,
        'description' => $_REQUEST['description'] ?? null,
        'start_date' => $_REQUEST['start_date'] ?? null,
        'end_date' => $_REQUEST['end_date'] ?? null,
        'price' => $_REQUEST['price'] ?? null,
        'start_time' => $_REQUEST['start_time'] ?? null,
        'end_time' => $_REQUEST['end_time'] ?? null,
        'image_file' => $image_file,
        'image_path' => $image_path,
        'image_file_02' => $image_file_02,
        'image_path_02' => $image_path_02,
        'image_file_03' => $image_file_03,
        'image_path_03' => $image_path_03,
        'image_file_04' => $image_file_04,
        'image_path_04' => $image_path_04,
        'image_file_05' => $image_file_05,
        'image_path_05' => $image_path_05,
        'image_file_06' => $image_file_06,
        'image_path_06' => $image_path_06,
        'image_file_07' => $image_file_07,
        'image_path_07' => $image_path_07,
        'image_file_08' => $image_file_08,
        'image_path_08' => $image_path_08,
        'image_file_09' => $image_file_09,
        'image_path_09' => $image_path_09,
        'image_file_10' => $image_file_10,
        'image_path_10' => $image_path_10,
        'image_file_11' => $image_file_11,
        'image_path_11' => $image_path_11,
        'image_file_12' => $image_file_12,
        'image_path_12' => $image_path_12,
        'image_file_13' => $image_file_13,
        'image_path_13' => $image_path_13,
        'image_file_14' => $image_file_14,
        'image_path_14' => $image_path_14,
        'image_file_15' => $image_file_15,
        'image_path_15' => $image_path_15,        
        'updated_user_id' => $_REQUEST['updated_user_id'] ?? null,
        'updated_date' => date('Y-m-d H:i:s')
    ];
    
    // Check if record already exists
    if ($event->existsByNameWhenEdit($data['name'], $data['id'])) {
      echo json_encode([
          "message" => "record_already_exists"
      ]);
      exit;
    } 
    
    // Update the record    
    if ($event->update($data)) {
        echo json_encode(['event' => []]);
    } else {
        echo json_encode(array("message" => "error_updating_record"));
    }    
} catch (Throwable $e) {
    http_response_code(401);
    die('EXPIRED');
}
?> 