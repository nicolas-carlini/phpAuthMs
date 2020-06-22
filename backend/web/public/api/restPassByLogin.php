<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  include '../../app/src/User.php';
  $User = new User();
  try {
    $payload = ["changePassword" => $User->changePasswordByLogin($_POST["email"], $_POST["pwd"], $_POST["newPwd"]), "error" => false];
  } catch (Exception $e) {
    $payload = ["changePassword" => false, "error" => true];
  } finally {
    echo json_encode($payload);
  }
} else {
  $payload = ["changePassword" => false, "error" => true];
  echo json_encode($payload);
}
