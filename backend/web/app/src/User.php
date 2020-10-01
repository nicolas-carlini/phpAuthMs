<?php

//debug valid email failed

@include './mails.php';
@include '../app/vendor/autoload.php';

class ApiMails{
  private $email;
  private $subject;
  private $message;
  function __construct($email,$subject,$message){
      $this->email=$email;
      $this->subject=$subject;
      $this->message=$message;
  }
  public function sendMail(){
      try {
          $data=array(
              'email'=>$this->email,
              'subject'=>$this->subject,
              'message'=>$this->message
          );
          $postdata = json_encode($data);
          $ch = curl_init("https://mailsnode.herokuapp.com/send");
          curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
          curl_setopt($ch, CURLOPT_POST, 1);
          curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
          curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
          $result = curl_exec($ch);
          curl_close($ch);

          return true;
      } catch (Exception $th) {
          echo $th;
          return false;
      }
  }
}

class User
{
  public function __construct()
  {
    $this->manager = new MongoDB\Driver\Manager('mongodb://191.168.0.2:27017');
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

      $validCode = rand(10000, 99999);

      $newUser = array(
        "name" => $name,
        "email" => $email,
        "password" => $this->hashPwd($pwd),
        "validCode" => rand(10000, 99999),
        "confirmEmail" => false,
        "isAdmin" => false
      );

      $this->bulk->insert($newUser);
      $result = $this->manager->executeBulkWrite('db.users', $this->bulk, $this->writeConcern);
      $this->bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
      $this->validateEmail($email, $validCode);
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
        $result = $this->manager->executeBulkWrite('db.users', $this->bulk, $this->writeConcern);
        $this->bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
        return true;
      }

      return false;
    } catch (Exception $e) {
      return false;
    }
  }

  //cambiar password atravez del email
  public function changePasswordByEmail($email, $validCode, $newPwd)
  {
    try {
      if ($this->confirmEmail($email, $validCode)) {
        $this->bulk->update(['email' => $email], ['$set' => ['password' => $this->hashPwd($newPwd)]]);
        $result = $this->manager->executeBulkWrite('db.users', $this->bulk, $this->writeConcern);
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
        $result = $this->manager->executeBulkWrite('db.users', $this->bulk, $this->writeConcern);
        $this->bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
        return true;
      }

      return false;
    } catch (Exception $e) {
      return false;
    }
  }

  //manda el email para recuperar la password
  public function sendEmail($email){
    return $this->validateEmail($email,$this->getValidCode($email));
  }

  private function getValidCode($email){
    $filter = ["email" => $email];
    $options = [
      "limit" => 1
    ];

    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $this->manager->executeQuery('db.users', $query);

    $document = $cursor->toArray();
    $document = $document[0];

    return $document->validCode;
  }

  //valida el codigo resivido 
  private function validCode($email, $validCode)
  {
    return $this->getValidCode($email) == $validCode;
  }

  //login reutilizable 
  private function loginCapsule($email, $pwd)
  {
    $filter = ["email" => $email];
    $options = [
      "limit" => 1
    ];

    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $this->manager->executeQuery('db.users', $query);

    $document = $cursor->toArray();
    $document = $document[0];

    setcookie('id', $document->_id, time()+(60*60*24*365));
    setcookie('name', $document->name, time()+(60*60*24*365));

    return $this->unhashPwd($pwd,$document->password) == $pwd && $document->confirmEmail;

  }

  //valida que el email no este registrado en la base de datos
  private function usableEmail($email)
  {
    $filter = ["email" => $email];
    $options = [];

    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $this->manager->executeQuery('db.users', $query);

    $document = $cursor->toArray();

    return (count($document) < 1)?true:false;
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

  //temas de emails
  private function validateEmail($email, $validCode)
  {
    $mail = new ApiMails($email,"codigo de validacion ","codigo de validacion $validCode");
    return $mail->sendMail();
  }

}

class Post {

  public function __construct()
  {
    $this->manager = new MongoDB\Driver\Manager('mongodb://191.168.0.2:27017');
    $this->writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
    $this->bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
  }

  public function addPost($content, $title){
    try{
      $newPost = array(
        "createdBy" => $_COOKIE['id'],
        "postName" => $_COOKIE['name'],
        "title" => $title,
        "content" => $content,
        "state" => true
      );

      $this->bulk->insert($newPost);
      $result = $this->manager->executeBulkWrite('db.posts', $this->bulk, $this->writeConcern);
      $this->bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);

      return true;
    }
    catch (Exception $e) {
      return false;
    }
  }

  public function addCommit($idPost, $content){
    try{
      $newCommit = array(
        "createdBy" => $_COOKIE['id'],
        "postName" => $_COOKIE['name'],
        "postId" => $idPost,
        "content" => $content,
        "state" => true
      );

      $this->bulk->insert($newCommit);
      $result = $this->manager->executeBulkWrite('db.commits', $this->bulk, $this->writeConcern);
      $this->bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);

      return true;
    }
    catch (Exception $e) {
      return false;
    }
  }

  public function deleteCommit($idCommit, $idCommiter){
    try {
      if($idCommiter == $_COOKIE['id']){
        $this->bulk->update(['_id' => $idCommit], ['$set' => ['state' => false]]);
        $result = $this->manager->executeBulkWrite('db.users', $this->bulk, $this->writeConcern);
        $this->bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);

        return true;
      }
      return false;
    } catch (Exception $e) {
      return false;
    }
  }

  public function deletePost($idPost, $idPoster){
    try {
        if($idPoster == $_COOKIE['id']){
        $this->bulk->update(['_id' => $idPost], ['$set' => ['state' => false]]);
        $result = $this->manager->executeBulkWrite('db.users', $this->bulk, $this->writeConcern);
        $this->bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);

        return true;
      }
      return false;
    } catch (Exception $e) {
      return false;
    }
  }

  public function getPosts(){
   try {
    $filter = ["state" => true];
    $options = [
      "limit" => 30
    ];

    $query = new MongoDB\Driver\Query($filter, $options);
    $cursor = $this->manager->executeQuery('db.posts', $query);

    return $cursor->toArray();
   } catch (Exception $e) {
     return [];
   }
  }

  public function getCommits($idPost){
    try {
      $filter = ["state" => true];
      $options = [];

      $query = new MongoDB\Driver\Query($filter, $options);
      $cursor = $this->manager->executeQuery('db.commits', $query);

      return $cursor->toArray();
    } catch (Exception $e) {
      return [];
    }
  }
}