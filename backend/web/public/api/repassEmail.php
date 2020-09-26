<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  include '../../app/src/User.php';
  $User = new User();
  try {
    $email = $_POST["email"];
    $newPwd = $_POST["newPwd"];
    $validCode = $_POST["validCode"];
    
    if($email != null && $newPwd != null && $validCode != null){
      $payload = ["changePassword" => $User->changePasswordByEmail($email, $validCode, $newPwd), "error" => false];
    }
  } catch (Exception $e) {
    $payload = ["changePassword" => false, "error" => true];
  } finally {
    echo json_encode($payload);
  }
} else {
  $payload = ["changePassword" => false, "error" => true];
  echo json_encode($payload);
}
