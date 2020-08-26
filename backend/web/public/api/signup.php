<?php

header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
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
      $payload = ["isRegistered" => false, "error" => true, "debug"=>$_POST];
    }
  } catch (Exception $e) {
    $payload = ["isRegistered" => false, "error" => true, "debug"=>$_POST];
  } finally {
    echo json_encode($payload);
  }
} else {
  $payload = ["isRegistered" => false, "error" => true, "debug"=>$_POST];
  echo json_encode($payload);
}
