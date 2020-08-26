<?php

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
