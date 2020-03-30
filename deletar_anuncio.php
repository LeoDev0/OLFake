<?php
require 'config.php';
require 'classes/class.anuncios.php';
$id_anuncio = $_GET['id'];
// $id_usuario = $_SESSION['user_id'];

// echo "Id do usuário: $id_usuario" . '<br>';
// echo "Id do anuncio: $id_anuncio";
if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
  $id_usuario = $_SESSION['user_id'];

  if (!empty($_GET['id'])) {
    $anuncio = new Anuncios($pdo);
    $anuncio->deletarAnuncio($id_anuncio, $id_usuario);
  }
}

header('Location: meus_anuncios.php');