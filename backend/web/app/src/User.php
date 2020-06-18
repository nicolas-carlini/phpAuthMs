<?php

namespace App\Acme;

use \Exception;

include '../app/vendor/autoload.php';

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
        try{
            return [$this->loginCapsule($email, $pwd),false];
        }catch(Exception $e){
            return [false,false];
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
            $this->validateEmail($email, $newUser["validCode"]);
            $this->bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
            return true;
        };

        return false;
    }  

    //cambiar password atravez del login
    public function changePasswordByLogin($email, $pwd, $newPwd)
    {
        try{
            if ($this->loginCapsule($email, $pwd)) {
                $this->bulk->update(['email' => $email], ['$set' => ['password' => $this->hashPwd($newPwd)]]);
                $result = $this->manager->executeBulkWrite('db.collection', $this->bulk, $this->writeConcern);
                $this->bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
                return true;
            }

            return false;
        }catch(Exception $e){
            return false;
        }
    }

    //cambiar password atravez de verificacion de email
    public function changePasswordByEmail($email, $pwd, $newPwd)
    {
        try{
            if ($this->loginCapsule($email, $pwd)) {
                $this->bulk->update(['email' => $email], ['$set' => ['password' => $this->hashPwd($newPwd)]]);
                $result = $this->manager->executeBulkWrite('db.collection', $this->bulk, $this->writeConcern);
                $this->bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
                return true;
            }

            return false;
        }catch(Exception $e){
            return false;
        }
    }

    //confirma email
    public function confirmEmail($email, $validCode)
    {
        try{
            if ($this->validCode($email, $validCode)) {
                $this->bulk->update(['email' => $email], ['$set' => ['confirmEmail' => true]]);
                $result = $this->manager->executeBulkWrite('db.collection', $this->bulk, $this->writeConcern);
                $this->bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
                return true;
            }

            return false;
        }catch(Exception $e){
            return false;
        }
    }

    //
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

    private function loginCapsule($email, $pwd)
    {
        $filter = ["email" => $email];
        $options = [
            "limit" => 1
        ];

        $query = new MongoDB\Driver\Query($filter, $options);
        $rows = $this->manager->executeQuery('db.collectionName', $query);

        var_dump($rows);

        return $this->validePassword($this->unhashPwd($pwd, $rows[0]["password"]), $pwd);
    }

    private function usableEmail($email)
    {
        $filter = ["email" => $email];
        $options = [];

        $query = new MongoDB\Driver\Query($filter, $options);
        $rows = $this->manager->executeQuery('db.collectionName', $query);

        var_dump($rows);

        $emailCount = 0;
        foreach ($rows as $document) {
            $emailCount++;
        }

        return ($emailCount > 0);
    }

    private function hashPwd($pwd)
    {
        $opciones = [
            'cost' => 12,
        ];

        return password_hash($pwd, PASSWORD_BCRYPT, $opciones);
    }

    private function unhashPwd($pwd, $hash)
    {
        return password_verify($pwd, $hash);
    }

    private function validePassword($vp, $pwd)
    {
        return ($vp == $pwd);
    }

    private function validateEmail($email, $validCode)
    {
        $from = "test@hostinger-tutorials.com";
        $to = $email;
        $subject = "Checking PHP mail";
        $message = "PHP mail works just fine" + $validCode;
        $headers = "From:" . $from;
        mail($to, $subject, $message, $headers);
        echo "The email message was sent.";
    }
}
