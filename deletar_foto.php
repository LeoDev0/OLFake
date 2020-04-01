<?php
require 'config.php';
require 'classes/class.anuncios.php';
$id_foto = $_GET['id'];

if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
  $id_usuario = $_SESSION['user_id'];

  if (isset($_GET['id']) && !empty($_GET['id'])) {
    $anuncio = new Anuncios($pdo);
    $id_anuncio = $anuncio->deletarFoto($id_foto, $id_usuario);
  }
}

if (isset($id_anuncio)) {
  header("Location: editar_anuncio.php?id=$id_anuncio");
} else {
  header('Location: meus_anuncios.php');
}
