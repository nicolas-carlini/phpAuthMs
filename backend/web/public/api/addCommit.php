<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: POST');
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("access-control-expose-headers: Set-Cookie");


include '../../app/src/User.php';
$post = new Post();
try {
  $content = $_POST['content'];
  $idPost = $_POST['idPost'];
  
  if(isset($content, $idPost)){
    $payload = ["isCommit" => $post->addCommit($idPost ,$content), "error" => false, "message"=>"todo bien"];
  }else{
    $payload = ["isCommit" => false, "error" => true, "message"=>"faltan variables"];
  }
} catch (Exception $e) {
  $payload = ["isCommit" => false, "error" => true, "message"=>"error fatal"];
} finally {
  echo json_encode($payload);
}
