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

  public function editarAnuncio($titulo, $categoria, $valor, $descricao, $estado, $fotos, $id_anuncio) {
    $sql = "UPDATE anuncios SET id_usuario = :id_usuario,
            titulo = :titulo, id_categoria = :categoria,
            valor = :valor, descricao = :descricao,
            estado = :estado WHERE id = :id_anuncio";
    $sql = $this->pdo->prepare($sql);
    $sql->bindValue(":id_usuario", $_SESSION['user_id']);
    $sql->bindValue(":titulo", $titulo);
    $sql->bindValue(":categoria", $categoria);
    $sql->bindValue(":valor", $valor);
    $sql->bindValue(":descricao", $descricao);
    $sql->bindValue(":estado", $estado);
    $sql->bindValue(":id_anuncio", $id_anuncio);
    $sql->execute();

    // Se vier alguma foto enviada pelo cliente, um redimensionamento
    // dessa imagem será feita antes de subida para o servidor  
    if (count($fotos) > 0) {
      for ($i = 0; $i < count($fotos['tmp_name']); $i++) {
        $tipo = $fotos['type'][$i];
        if (in_array($tipo, ['image/jpeg', 'image/jpg', 'image/png'])) {
          $nome_foto = md5(time().rand(0,9999)) . '.jpg';
          move_uploaded_file($fotos['tmp_name'][$i], 'assets/images/anuncios/' . $nome_foto);

          list($largura_original, $altura_original) = getimagesize('assets/images/anuncios/' . $nome_foto);
          $ratio = $largura_original / $altura_original;

          $largura = 500;
          $altura = 500;

          if ($largura / $altura > $ratio) {
            $largura = $altura * $ratio;
          } else {
            $altura = $largura / $ratio;
          }

          $img = imagecreatetruecolor($largura, $altura);
          if ($tipo == 'image/jpeg') {
            $img_original = imagecreatefromjpeg('assets/images/anuncios/' . $nome_foto);
          } else {
            $img_original = imagecreatefrompng('assets/images/anuncios/' . $nome_foto);
          }

          imagecopyresampled($img, $img_original, 0, 0, 0, 0,
           $largura, $altura, $largura_original, $altura_original);

          imagejpeg($img, 'assets/images/anuncios/' . $nome_foto, 80);

          $sql = "INSERT INTO anuncios_imagens SET id_anuncio = :id_anuncio, url = :url";
          $sql = $this->pdo->prepare($sql);
          $sql->bindValue(":id_anuncio", $id_anuncio);
          $sql->bindValue(":url", $nome_foto);
          $sql->execute();
        }
      }
    }
  }

  public function getAnuncio($id_anuncio, $id_usuario) {
    $sql = "SELECT * FROM anuncios WHERE id = :id_anuncio AND id_usuario = :id_usuario";
    $sql = $this->pdo->prepare($sql);
    $sql->bindValue(":id_anuncio", $id_anuncio);
    $sql->bindValue(":id_usuario", $id_usuario);
    $sql->execute();

    if ($sql->rowCount() > 0) {
      $array = $sql->fetch();
      $array['fotos'] = array();

      $sql = "SELECT id, url FROM anuncios_imagens WHERE id_anuncio = :id_anuncio";
      $sql = $this->pdo->prepare($sql);
      $sql->bindValue(":id_anuncio", $id_anuncio);
      $sql->execute();

      if ($sql->rowCount() > 0) {
        $array['fotos'] = $sql->fetchAll();
      }

      return $array;
    } else {
      header('Location: meus_anuncios.php');
    }
  }

  public function deletarFoto($id_foto, $id_usuario) {
    $id_anuncio = 0;

    $sql = "SELECT id_anuncio, url FROM anuncios_imagens WHERE id = :id_foto";
    $sql = $this->pdo->prepare($sql);
    $sql->bindValue(":id_foto", $id_foto);
    $sql->execute();

    if ($sql->rowCount() > 0) {
      $dados_foto = $sql->fetch();
      $id_anuncio = $dados_foto['id_anuncio'];
      $url = $dados_foto['url'];
    }

    $sql = "DELETE FROM anuncios_imagens WHERE id = :id_foto";
    $sql = $this->pdo->prepare($sql);
    $sql->bindValue(":id_foto", $id_foto);
    $sql->execute();

    unlink('assets/images/anuncios/' . $url);
    return $id_anuncio;

  }
}