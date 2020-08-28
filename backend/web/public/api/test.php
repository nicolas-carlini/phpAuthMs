<?php


header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  try {
    include '../../app/src/User.php';

    $user = new User();
  
    $payload = ["isRegistered" => $user->validateEmail(), "error" => false];
  } catch (Exception $e) {
    $payload = ["isRegistered" => false, "error" => true];
  } finally {
    echo json_encode($payload);
  }
} else {
  $payload = ["isRegistered" => false, "error" => true];
  echo json_encode($payload);
}
