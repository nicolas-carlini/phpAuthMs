<?php

header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  include '../../app/src/User.php';
  $User = new User();
  try {
    $email = $_POST["email"];
    $name = $_POST["name"];
    $pwd = $_POST["pwd"];
    if($pwd != null && $email != null && $name != null){
      $payload = ["isRegistered" => $User->signup($email, $name, $pwd), "error" => false];
    }
    else{
      $payload = ["isRegistered" => false, "error" => true];
    }
  } catch (Exception $e) {
    $payload = ["isRegistered" => false, "error" => true];
  } finally {
    echo json_encode($payload);
  }
} else {
  $payload = ["isRegistered" => false, "error" => true];
  echo json_encode($payload);
}
