<?php

include './DB.php';

class User{
    function signin($email, $name, $pwd) {
        
    }

    function singup($email, $name, $pwd){
        if($this->usableEmail($email)){
            $newUser = array(
            "name"=>$name,
            "email"=>$email,
            "pwd"=>$this->hashPwd($pwd)
            );
        };
    }

    function usableEmail($email){
        $condicion = array("email"=>$email);

        return 0;
    }

    function hashPwd($pwd){
        $opciones = [
            'cost' => 12,
        ];
        
        return password_hash($pwd, PASSWORD_BCRYPT, $opciones);
    }

    function unhashPwd($pwd, $hash){
        return password_verify($pwd , $hash );
    }

    function validateEmail($email){
        $subj = "validar cuenta";
        $message = "pepe";

        mail($email, $subj, $message);
    }
}
