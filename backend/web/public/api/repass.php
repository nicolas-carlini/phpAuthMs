<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header('Access-Control-Allow-Methods: POST');
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

  include '../../app/src/User.php';
  $User = new User();
  try {
    $email = $_POST["email"];
    $newPwd = $_POST["newPwd"];
    $pwd = $_POST["pwd"];
    
    if(isset($email ,$newPwd ,pwd)){
      $payload = ["changePassword" => $User->changePasswordByLogin($email, $pwd, $newPwd), "error" => false];
    }else{
      $payload = ["changePassword" => false, "error" => true];
    }
  } catch (Exception $e) {
    $payload = ["changePassword" => false, "error" => true];
  } finally {
    echo json_encode($payload);
  }

