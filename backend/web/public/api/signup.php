<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  include '../../app/src/User.php';
  $User = new User();

  try {
    $payload = ["isRegistered" => $User->signup($_POST["email"], $_POST["name"], $_POST["pwd"]), "error" => false];
  } catch (Exception $e) {
    $payload = ["isRegistered" => false, "error" => true];
  } finally {
    echo json_encode($payload);
  }
} else {
  $payload = ["isRegistered" => false, "error" => true];
  echo json_encode($payload);
}
