<?php


header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("access-control-expose-headers: Set-Cookie");

try {
  include '../../app/src/User.php';

  $post = new Post();

} catch (Exception $e) {
  $payload = ["isRegistered" => false, "error" => true];
} finally {
  echo json_encode($payload);
}
