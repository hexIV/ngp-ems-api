<?php

// parse URL to see which Controller we need to access
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

if (count($uri) != 4) {
  echo json_encode(['status' => 404, 'data' => 'Incorrect URL']);
  exit;
}

// Create instance of Controller
$className = strtoupper($uri[3]);
include "Controllers/" . $className . "Controller.php";
$class = new $className();

// based on the $_SERVER['REQUEST_METHOD'] we call the methods accordingly
// GET to SELECT
// POST to INSERT
// PUT to UPDATE
// DELETE to DELETE
switch (strtolower($_SERVER['REQUEST_METHOD'])) {
  case "get":
    try {
      echo json_encode(['status' => 200, 'data' => $class->get($_GET['id'] ?? null)]);
    } catch (Exception $e) {
      echo json_encode(['status' => 500, 'data' => $e->getMessage()]);
    }
    break;
  case "put":
    $data = json_decode(file_get_contents("php://input"), true);
    try {
      echo json_encode(['status' => 200, 'data' => $class->edit($data)]);
    } catch (Exception $e) {
      echo json_encode(['status' => 500, 'data' => $e->getMessage()]);
    }
    break;
  case "post":
    $data = json_decode(file_get_contents("php://input"), true);
    try {
      echo json_encode(['status' => 200, 'data' => $class->add($data)]);
    } catch (Exception $e) {
      echo json_encode(['status' => 500, 'data' => $e->getMessage()]);
    }
    break;
  case "delete":
    try {
      echo json_encode(['status' => 200]);
    } catch (Exception $e) {
      echo json_encode(['status' => 500, 'data' => $e->getMessage()]);
    }
    break;
}