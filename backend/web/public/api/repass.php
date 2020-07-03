<?php


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  include '../../app/src/User.php';
  $User = new User();
  try {
    $email = $_POST["email"];
    $newPwd = $_POST["newPwd"];
    $pwd = $_POST["pwd"];
    
    $payload = ["changePassword" => $User->changePasswordByLogin($email, $pwd, $newPwd), "error" => false];
  } catch (Exception $e) {
    $payload = ["changePassword" => false, "error" => true];
  } finally {
    echo json_encode($payload);
  }
} else {
  $payload = ["changePassword" => false, "error" => true];
  echo json_encode($payload);
}
