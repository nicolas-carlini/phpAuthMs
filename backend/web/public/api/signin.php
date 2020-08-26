<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  include '../../app/src/User.php';
  $User = new User();
  try {
    $email = $_POST["email"];
    $pwd = $_POST["pwd"];
    if($pwd != null && $email != null){
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
