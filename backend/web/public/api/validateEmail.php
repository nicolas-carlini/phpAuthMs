<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  include '../../app/src/User.php';
  $User = new User();

  try {
    $payload = ["isConfirm" => $User->confirmEmail($_POST["email"], $_POST["code"]), "error" => false];
  } catch (Exception $e) {
    $payload = ["isConfirm" => false, "error" => true];
  } finally {
    echo json_encode($payload);
  }
} else {
  $payload = ["isConfirm" => false, "error" => true];
  echo json_encode($payload);
}
