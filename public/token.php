<?php

require '../vendor/autoload.php';
require './cors.php';

use app\database\Connection;
use Firebase\JWT\JWT;

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__, 2));
$dotenv->load();

$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
$password = filter_input(INPUT_POST, 'password', FILTER_UNSAFE_RAW);

$conn = new Connection();
$db = $conn->connect();

$prepare = $db->prepare("select * from user where email = :email");
$prepare->execute([
  'email' => $email
]);

$userFound = $prepare->fetch();

if(!$userFound) {  
  http_response_code(401);
}

if(!password_verify($password, $userFound->password)) {
  http_response_code(401);
}

$payload = [
  "exp" => time() + 1200,
  "iat" => time(),
  "email" => $email
];

$encode = JWT::encode($payload, $_ENV['KEY'], 'HS256');

echo json_encode($encode);

?>