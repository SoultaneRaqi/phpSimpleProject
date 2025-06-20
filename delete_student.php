<?php

require_once"connection.php";

session_start();



if (!isset($_GET["id"])){
    die("No id provided");
}

$id = $_GET['id'];


$stmt = $pdo->prepare("DELETE  FROM students WHERE id = ?");
$stmt->execute([$id]);


header("Location: index.php");

exit();
?>