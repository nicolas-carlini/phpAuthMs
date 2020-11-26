<?php

header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Access-control-expose-headers: Set-Cookie");

include '../../app/src/User.php';
$User = new User();
$email = $_POST["email"];
$pwd = $_POST["pwd"];

try {
  if(isset($pwd ,$email)){
    $result = $User->signin($email,$pwd);
    $payload = ["isLogged" => $result[0], "error" => $result[1], "message"=>"Todo salio bien"];
  }
  else{
    $payload = ["isLogged" => false, "error" => true, "message"=>"faltan datos"];
  }
} catch (Exception $e) {
  $payload = ["isLogged" => false, "error" => true, "message"=>"error falta"];
} finally {
  echo json_encode($payload);
}
