<?php

namespace app\database;

use PDO;

class Connection
{
  public $conn;

  public function connect()
  {
    $localHostList = array(
      '127.0.0.1',
      'localhost',
      '::1'
    );

    if (in_array($_SERVER['REMOTE_ADDR'], $localHostList)) {
      // DEV
      //$this->conn = new PDO("mysql:host=179.188.16.2;port=3306;dbname=arteemp_d","arteemp_d","Stefanini@10",[
      $this->conn = new PDO("mysql:host=localhost;port=3306;dbname=arteemp_d", "root", "Stefanini@10", [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
      ]);
    } else {
      // PROD
      $this->conn = new PDO("mysql:host=179.188.16.2;port=3306;dbname=arteemp_d", "arteemp_d", "Stefanini@10", [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
      ]);
    }

    return $this->conn;
  }
}
