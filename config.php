<?php
$dbuser = "root";
$dbpass = "";
$dbname = "classificados";
$host = "localhost";
$dsn = "mysql:dbname=$dbname;host=$host";
$options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];

try {
  $pdo = new PDO($dsn, $dbuser, $dbpass, $options);
} catch (PDOException $e) {
  die('Erro: ' . $e->getMessage());
}