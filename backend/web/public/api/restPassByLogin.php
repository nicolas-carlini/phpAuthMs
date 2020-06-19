<?php
include '../app/vendor/autoload.php';
$User = new App\Acme\User();

try {
  $payload = ["isLogged" => $User->signin($_POST["email"], $_POST["pwd"]), "error" => false];
} catch (Exception $e) {
  $payload = ["isLogged" => false, "error" => true];
} finally {
  echo json_encode($payload);
}
