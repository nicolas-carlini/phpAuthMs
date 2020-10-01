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
    $idCommiter = $_POST['idCommiter'];
    $idCommit = $_POST['idCommit'];

    if(isset($idCommit, $idCommiter)){
      $payload = ["isCommit" => $post->deleteCommit($idCommit, $idCommiter), "error" => false];
    }else{
      $payload = ["isCommit" => false, "error" => true];
    }
  } catch (Exception $e) {
    $payload = ["isCommit" => false, "error" => true];
  } finally {
    echo json_encode($payload);
  }
} else {
  $payload = ["isCommit" => false, "error" => true];
  echo json_encode($payload);
}