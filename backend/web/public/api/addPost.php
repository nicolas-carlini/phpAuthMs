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
  $title = $_POST['title'];
  
  if(isset($content, $title)){
    $payload = ["isPost" => $post->addPost($title, $content), "error" => false, "message"=>"todo bien"];
  }else{
    $payload = ["isPost" => false, "error" => true, "message"=>"no setiado"];
  }
} catch (Exception $e) {
  $payload = ["isPost" => false, "error" => true, "message"=>"fallo algo"];
} finally {
  echo json_encode($payload);
}
