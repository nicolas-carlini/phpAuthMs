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
  $email = $_POST["email"];
  $pwd = $_POST["pwd"];
  try {
    if($pwd != null && $email != null){
      $result = $User->signin($email,$pwd);
      $payload = ["isLogged" => $result[0], "error" => $result[1], "email"=>$email, "pwd"=>$pwd];
    }
    else{
      $payload = ["isLogged" => false, "error" => true, "email"=>$email, "pwd"=>$pwd];
    }
  } catch (Exception $e) {
    echo $e;
    $payload = ["isLogged" => false, "error" => true, "email"=>$email, "pwd"=>$pwd];
  } finally {
    echo json_encode($payload);
  }
} else {
  $payload = ["isLogged" => false, "error" => true, "postman"=> true];
  echo json_encode($payload);
}
