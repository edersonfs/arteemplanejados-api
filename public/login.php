<?php

require '../vendor/autoload.php';
require './cors.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use app\database\Connection;

// CORS headers are handled in public/cors.php

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__, 2));
$dotenv->load();

$authorization = $_SERVER['HTTP_AUTHORIZATION'];

$token = str_replace('Bearer ', '', $authorization);

// include database and object files
include_once '../app/database/Connection.php';
include_once 'model/user.php';
 
// instantiate database and category object
$conn = new Connection();
$db = $conn->connect();

try {       
    $decoded = JWT::decode($token, new Key($_SERVER['KEY'], 'HS256'));

    // initialize object
    $user = new User($db);    

    // parameters
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        
    $stmt =  $db->prepare("SELECT * FROM user where email = :email");
    $stmt -> execute([
      'email' => $email
    ]);    
    $num = $stmt -> rowCount();
    
    // check if more than 0 record found
    if ($num > 0) {
    
        $users_arr = array();
        $users_arr["login"] = array();
    
        // retrieve our table contents
        // fetch() is faster than fetchAll()
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            // extract row
            // this will make $row['name'] to
            // just $name only
            extract($row);
    
            $user_item = array(
                "id" => $id,
                "name" => $name,
                "email" => $email,
                "group_id" => $group_id,
                "company_id" => $company_id
            );
    
            array_push($users_arr["login"], $user_item);
        }
    
        echo json_encode($users_arr);
    } else {
        echo json_encode(
            array("message" => "No user found.")
        );
    }    
} catch (Throwable $e) {
  // echo $e;    
    // if ($e->getMessage() === 'Expired token') {
        http_response_code(401);
        die('EXPIRED' . $e);
    // }
}
 

?>