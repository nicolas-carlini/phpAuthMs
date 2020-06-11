<?php

try{
    $uri = "mongodb+srv://nicolas:carlini1@cluster0-qnsci.gcp.mongodb.net/test?retryWrites=true&w=majority";
    $client = new MongoDB\Client($uri);
    $DB = $client->test;
    $DBstate = true;
}catch(e){
    $DBstate = false;
    $DBmessage = e;
    exit();
}

?>