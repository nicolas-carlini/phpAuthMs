<?php
include '../app/vendor/autoload.php';
$User = new App\Acme\User();

try {
  $payload = ["isConfirm" => $User->confirmEmail($_POST["email"], $_POST["code"]), "error" => false];
} catch (Exception $e) {
  $payload = ["isConfirm" => false, "error" => true];
} finally {
  echo json_encode($payload);
}
