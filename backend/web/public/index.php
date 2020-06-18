<?php

include '../app/vendor/autoload.php';
$User = new App\Acme\User();

/*
$bulk->delete([]);
$bulk->insert(['_id' => 1]);
$bulk->insert(['_id' => 2]);
$bulk->insert(['_id' => 3, 'hello' => 'world']);
$bulk->update(['_id' => 3], ['$set' => ['hello' => 'earth']]);
$bulk->insert(['_id' => 4, 'hello' => 'pluto']);
$bulk->update(['_id' => 4], ['$set' => ['hello' => 'moon']]);
$bulk->insert(['_id' => 3]);
$bulk->insert(['_id' => 4]);
$bulk->insert(['_id' => 5]);

$result = $manager->executeBulkWrite('db.collection', $bulk, $writeConcern);

printf("Inserted %d document(s)\n", $result->getInsertedCount());
printf("Updated  %d document(s)\n", $result->getModifiedCount());
*/

?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Carlini</title>
    </head>
    <body>
        <h1>Api</h1>
    </body>
</html>