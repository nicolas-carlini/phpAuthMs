<?php

if(true){
  header('Access-Control-Allow-Origin: *');
  header("Content-Type: application/json; charset=UTF-8");
  header("Access-Control-Allow-Methods: POST");
  header("Access-Control-Max-Age: 3600");
  header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  include '../../app/src/User.php';
  $User = new User();
  try {
    $result = $User->signin($_POST["email"], $_POST["pwd"]);
    $payload = ["isLogged" => $result[0], "error" => $result[1]];
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
