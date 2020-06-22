<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  include '../../app/src/User.php';
  $User = new User();

  try {
    $payload = ["isLogged" => $User->signin($_POST["email"], $_POST["pwd"]), "error" => false];
  } catch (Exception $e) {
    $payload = ["isLogged" => false, "error" => true];
  } finally {
    echo json_encode($payload);
  }
} else {
  $payload = ["isLogged" => false, "error" => true];
  echo json_encode($payload);
}
