<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: POST');
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  include '../../app/src/User.php';
  $post = new Post();
  try {
    $payload = ["isPost" => $post->addPost(), "error" => false];
  } catch (Exception $e) {
    $payload = ["isPost" => false, "error" => true];
  } finally {
    echo json_encode($payload);
  }
} else {
  $payload = ["isPost" => false, "error" => true];
  echo json_encode($payload);
}