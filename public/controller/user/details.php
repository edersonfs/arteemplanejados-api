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
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

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
include_once '../../model/user.php';
include_once '../../utils/utils.php';
 
// instantiate database and category object
$conn = new Connection();
$db = $conn->connect();

try {        
    $decoded = JWT::decode($token, new Key($_SERVER['KEY'], 'HS256'));

    $group_id = isset($decoded->group_id) ? (int) $decoded->group_id : null;
    $company_id = isset($decoded->company_id) ? (int) $decoded->company_id : null;

    // initialize object
    $user = new User($db);
    $utils = new Utils();    

    $id = filter_input(INPUT_GET, 'id', FILTER_DEFAULT); // FILTER_SANITIZE_STRING is deprecated
    $id = "$id"; // Add wildcards for LIKE search

    $stmt = $user->getById($id, $group_id, $company_id);

    if (is_array($stmt)) {
        $num = count($stmt);
    } else {
        $num = $stmt->rowCount();
    }

    $stmt = $utils->utf8ize($stmt);
    
    // check if more than 0 record found
    if($num > 0) {    
        echo json_encode(['user' => [$stmt]]);
    } else {
        echo json_encode(
            array("message" => "record_does_not_exist")
        );
    }    
} catch (Throwable $e) {
    http_response_code(401);
    die('EXPIRED' . $e);
}
 
?>