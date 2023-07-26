<?php

// parse URL to see which Controller we need to access
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );

// Create instance of Controller
$className = strtoupper($uri[3]);
include "Controllers/" . $className . "Controller.php";
$class = new $className();

// based on the request type we call the methods accordingly
// GET to SELECT
// POST to INSERT
// PUT to UPDATE
// DELETE to DELETE
switch (strtolower($_SERVER['REQUEST_METHOD'])) {
  case "get":
    echo json_encode($class->get($_GET['id'] ?? null));
    break;
  case "put":
    echo json_encode($class->update($_GET['id'], $_SERVER['PUT']));
    break;
  case "post":
    echo json_encode($class->add($_GET['id'] ?? null));
    break;
  case "delete":
    echo json_encode($class->get($_GET['id'] ?? null));
    break;
}