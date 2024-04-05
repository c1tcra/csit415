<?php
//connect to db (this is excluse for my localhost but can be used as a tempalte)
$host = 'localhost'; //host
$dbname = 'swe2'; //db name (same as we use same dump)
$user = 'selman'; //db user
$pass = '123'; //db pass

//dsn for pdo
$dsn = "mysql:host=$host;dbname=$dbname";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("ERROR: Could not connect. " . $e->getMessage());
}
?>