<?php

class Anuncios {
  protected $pdo;

  public function __construct($pdo) {
    $this->pdo = $pdo;
  }

  public function getMeusAnuncios() {
    $array = array();

    $sql = "SELECT *, 
    (SELECT anuncios_imagens.url FROM anuncios_imagens 
     WHERE anuncios_imagens.id_anuncio = anuncios.id LIMIT 1) 
     AS url 
     FROM anuncios 
     WHERE id_usuario = :id_usuario";
    $sql = $this->pdo->prepare($sql);
    $sql->bindValue(":id_usuario", $_SESSION['user_id']);
    $sql->execute();

    if ($sql->rowCount() > 0) {
      $array = $sql->fetchAll();
    }

    return $array;
  }

  public function addAnuncio($titulo, $categoria, $valor, $descricao, $estado) {
    $sql = "INSERT INTO anuncios (id_usuario ,titulo, id_categoria, valor, descricao, estado)
     VALUES (:id_usuario, :titulo, :categoria, :valor, :descricao, :estado)";
    $sql = $this->pdo->prepare($sql);
    $sql->bindValue(":id_usuario", $_SESSION['user_id']);
    $sql->bindValue(":titulo", $titulo);
    $sql->bindValue(":categoria", $categoria);
    $sql->bindValue(":valor", $valor);
    $sql->bindValue(":descricao", $descricao);
    $sql->bindValue(":estado", $estado);
    $sql->execute();
  }

  public function deletarAnuncio($id_anuncio, $id_usuario) {
    $sql = "DELETE FROM anuncios_imagens WHERE id_anuncio = :id_anuncio";
    $sql = $this->pdo->prepare($sql);
    $sql->bindValue(":id_anuncio", $id_anuncio);
    $sql->execute();

    $sql = "DELETE FROM anuncios WHERE id = :id_anuncio AND id_usuario = :id_usuario";
    $sql = $this->pdo->prepare($sql);
    $sql->bindValue(":id_anuncio", $id_anuncio);
    $sql->bindValue(":id_usuario", $id_usuario);
    $sql->execute();
  }
}