<?php
$pdo = new PDO(
    "mysql:host=db;dbname=geocap;charset=utf8mb4",
    "user",
    "user"
);

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>