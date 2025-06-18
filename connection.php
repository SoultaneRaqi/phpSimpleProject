<?php
$db_name = "cc3_db";
$host = "localhost";
$user = "root";
$password  = "";


try{
    $pdo = new PDO("mysql:host=$host;dbname=$db_name",$user , $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
}catch(PDOException  $e){
    echo"Error : ". $e->getMessage();
}

?>