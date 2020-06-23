<?php

//debug valid email failed 
//

@include '../app/vendor/autoload.php';

class User
{

  public function __construct()
  {
    $this->manager = new MongoDB\Driver\Manager('mongodb+srv://nicolas:tkiGFGfVdyBvF18E@cluster0-qnsci.gcp.mongodb.net/PHPNGINX?retryWrites=true&w=majority');
    $this->writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
    $this->bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
  }

  //login
  public function signin($email, $pwd)
  {
    try {
      return [$this->loginCapsule($email, $pwd), false];
    } catch (Exception $e) {
      return [false, false];
    }
  }

  //registro
  public function signup($email, $name, $pwd)
  {
    if ($this->usableEmail($email)) {
      $newUser = array(
        "name" => $name,
        "email" => $email,
        "password" => $this->hashPwd($pwd),
        "validCode" => rand(10000, 99999),
        "confirmEmail" => false
      );

      $this->bulk->insert($newUser);
      $result = $this->manager->executeBulkWrite('db.collection', $this->bulk, $this->writeConcern);
      //$this->validateEmail($email, $newUser["validCode"]);
      $this->bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
      return true;
    };

    return false;
  }

  //cambiar password atravez del login
  public function changePasswordByLogin($email, $pwd, $newPwd)
  {
    try {
      if ($this->loginCapsule($email, $pwd)) {
        $this->bulk->update(['email' => $email], ['$set' => ['password' => $this->hashPwd($newPwd)]]);
        $result = $this->manager->executeBulkWrite('db.collection', $this->bulk, $this->writeConcern);
        $this->bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
        return true;
      }

      return false;
    } catch (Exception $e) {
      return false;
    }
  }

  //cambiar password atravez de verificacion de email
  public function changePasswordByEmail($email, $validCode, $newPwd)
  {
    try {
      if ($this->validCode($email, $validCode)) {
        $this->bulk->update(['email' => $email], ['$set' => ['password' => $this->hashPwd($newPwd)]]);
        $result = $this->manager->executeBulkWrite('db.collection', $this->bulk, $this->writeConcern);
        $this->bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
        return true;
      }

      return false;
    } catch (Exception $e) {
      return false;
    }
  }

  //confirma email
  public function confirmEmail($email, $validCode)
  {
    try {
      if ($this->validCode($email, $validCode)) {
        $this->bulk->update(['email' => $email], ['$set' => ['confirmEmail' => true]]);
        $result = $this->manager->executeBulkWrite('db.collection', $this->bulk, $this->writeConcern);
        $this->bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
        return true;
      }

      return false;
    } catch (Exception $e) {
      return false;
    }
  }

  //valida el codigo resivido 
  private function validCode($email, $validCode)
  {
    $filter = ["email" => $email];
    $options = [
      "limit" => 1
    ];

    $query = new MongoDB\Driver\Query($filter, $options);
    $rows = $this->manager->executeQuery('db.collectionName', $query);

    return ($rows[0]["validCode"] == $validCode);
  }

  //login reutilizable 
  private function loginCapsule($email, $pwd)
  {
    $email = "nicolascarlini1@gmail.com";
    $pwd = "pepe";
    $filter = ["email" => $email];
    $options = [
      "limit" => 1
    ];

    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $this->manager->executeQuery('db.collectionName', $query);

    foreach ($cursor as $document) {
      var_dump($document);
    }

    return $cursor;
  }

  //valida que el email no este registrado en la base de datos
  private function usableEmail($email)
  {
    $filter = ["email" => $email];
    $options = [];

    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $this->manager->executeQuery('db.collectionName', $query);

    foreach ($cursor as $document) {
      var_dump($document);
    }

    return true;
  }

  //hashea la password
  private function hashPwd($pwd)
  {
    $opciones = [
      'cost' => 12,
    ];

    return password_hash($pwd, PASSWORD_BCRYPT, $opciones);
  }

  //deshashea la password
  private function unhashPwd($pwd, $hash)
  {
    return password_verify($pwd, $hash);
  }

  //valida la password(es mas comodo asi la verdad y ademas puedo agregar mas logica en el futuro de ser necesario)
  private function validePassword($vp, $pwd)
  {
    return ($vp == $pwd);
  }

  //temas de emails, alta paja la verdad
  private function validateEmail($email, $validCode)
  {
    $from = "test@hostinger-tutorials.com";
    $to = $email;
    $subject = "Checking PHP mail";
    $message = "PHP mail works just fine" + $validCode;
    $headers = "From:" . $from;
    mail($to, $subject, $message, $headers);
  }
}
