<?php

header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  include '../../app/src/User.php';
  $User = new User();
  $email = $_POST["email"];
  $pwd = $_POST["pwd"];

  try {
    if(isset($pwd ,$email)){
      $result = $User->signin($email,$pwd);
      $payload = ["isLogged" => $result[0], "error" => $result[1]];
    }
    else{
      $payload = ["isLogged" => false, "error" => true];
    }
  } catch (Exception $e) {
    echo $e;
    $payload = ["isLogged" => false, "error" => true];
  } finally {
    echo json_encode($payload);
  }
} else {
  $payload = ["isLogged" => false, "error" => true];
  echo json_encode($payload);
}
