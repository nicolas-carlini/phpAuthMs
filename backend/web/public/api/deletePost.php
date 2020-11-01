<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: POST');
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


include '../../app/src/User.php';
$post = new Post();
try {
  $idPost = $_POST['idPost'];
  $idPoster = $_POST['idPoster'];

  if(isset($idPost, $idPoster)){
    $payload = ["isPost" => $post->deletePost($idPost, $idPoster), "error" => false];
  }else{
    $payload = ["isPost" => false, "error" => true];
  }
} catch (Exception $e) {
  $payload = ["isPost" => false, "error" => true];
} finally {
  echo json_encode($payload);
}
