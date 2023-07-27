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
      $statement->bind_param(join('', array_column($params, 'type')), ...array_column($params, 'value'));
    }

    $statement->execute();
    $result = $statement->get_result();

    return $result->fetch_all(MYSQLI_ASSOC);
  }

  public function insert($query, $params = []) {
    $statement = $this->conn->prepare($query);

    if (!empty($params)) {
      $statement->bind_param(join('', array_column($params, 'type')), ...array_column($params, 'value'));
    }

    $statement->execute();

    return $statement->insert_id;
  }

  public function update($query, $params = []) {
    $statement = $this->conn->prepare($query);

    if (!empty($params)) {
      $statement->bind_param(join('', array_column($params, 'type')), ...array_column($params, 'value'));
    }

    $statement->execute();

    return true;
  }

  public function delete($query, $params = []) {
    $statement = $this->conn->prepare($query);

    if (!empty($params)) {
      $statement->bind_param(join('', array_column($params, 'type')), ...array_column($params, 'value'));
    }

    $statement->execute();

    return true;
  }
}