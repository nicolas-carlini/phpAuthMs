<?php
include '../app/vendor/autoload.php';
$User = new App\Acme\User();

try {
  $payload = ["isRegistered" => $User->signup($_POST["email"], $_POST["name"], $_POST["pwd"]), "error" => false];
} catch (Exception $e) {
  $payload = ["isRegistered" => false, "error" => true];
} finally {
  echo json_encode($payload);
}
