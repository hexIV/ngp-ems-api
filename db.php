<?php

require __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

class DB {
  public $conn = null;

  public function __construct() {
    $username = $_ENV['DB_USERNAME'];
    $password = $_ENV['DB_PASSWORD'];
    $host = $_ENV['DB_HOST'];
    $dbName = $_ENV['DB_NAME'];
    
    try {
      $this->conn = mysqli_connect($host, $username, $password, $dbName);
    } catch (Exception $e) {
      echo $e->getMessage();
      exit;
    }
  }

  public function select($query, $params = []) {
    $statement = $this->conn->prepare($query);

    if (!empty($params)) {
      foreach ($params as $param) {
        $statement->bind_param($param['type'], $param['value']);
      }
    }

    $statement->execute();
    $result = $statement->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
  }
}