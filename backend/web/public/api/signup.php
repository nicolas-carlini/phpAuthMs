<?php

header("Content-Type: application/json; charset=UTF-8");
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: POST');
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

  include '../../app/src/User.php';
  $User = new User();
  try {
    $email = $_POST["email"];
    $name = $_POST["name"];
    $pwd = $_POST["pwd"];

    var_dump($_POST);

    if(isset($pwd ,$email ,$name)){
      $payload = ["isRegistered" => $User->signup($email, $name, $pwd), "error" => false, "message"=>"Todo salio bien"];
    }
    else{
      $payload = ["isRegistered" => false, "error" => true, "message"=>"faltan campos"];
    }
  } catch (Exception $e) {
    $payload = ["isRegistered" => false, "error" => true, "message"=>"error inesperado"];
  } finally {
    echo json_encode($payload);
  }
