<?php

header('Content-Type: application/json');

$dsn = "mysql:host=localhost;dbname=ecommerce";
$user = "root";
$password = "";

$pdo = new PDO($dsn, $user, $password);

