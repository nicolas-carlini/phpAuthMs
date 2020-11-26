<?php
header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("access-control-expose-headers: Set-Cookie");


include '../../app/src/User.php';
$User = new User();
try {
  $email = $_POST["email"];

  if(isset($email)){
    $payload = ["changePassword" => $User->sendEmail($email), "error" => false];
  }else{
    $payload = ["changePassword" => false, "error" => true];
  }
} catch (Exception $e) {
  $payload = ["changePassword" => false, "error" => true];
} finally {
  echo json_encode($payload);
}

