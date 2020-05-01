<?php
session_start();

$dbuser = "root";
$dbpass = "";
$dbname = "classificados";
$host = "localhost";
$dsn = "mysql:dbname=$dbname;host=$host;charset=utf8";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
  $pdo = new PDO($dsn, $dbuser, $dbpass, $options);
} catch (PDOException $e) {
  die('Erro: ' . $e->getMessage());
}