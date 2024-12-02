<?php

require_once __DIR__ . '/vendor/autoload.php';

$dsn='mysql:host=localhost;port=3306;dbname=e-commercedb';
$user='ghada';
$pass='STUDIprojet';
$option=array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
);

try{

    $con = new PDO($dsn, $user, $pass, $option);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   // echo 'You are connected ';
}

catch(PDOException $e){
    echo 'Failed to connect'  .$e->getMessage();
}



try {

    $client = new MongoDB\Client("mongodb+srv://ghada:STUDIprojet@cluster0.c8df6.mongodb.net/");
    $db = $client->selectDatabase('e-commercedb-mongo');
    $collection = $db->selectCollection('Activities_USER_Log');

} catch (Exception $e) {

    die("Error connecting to MongoDB: " . $e->getMessage());
}

