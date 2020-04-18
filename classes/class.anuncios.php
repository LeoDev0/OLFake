<?php

class Anuncios {
  protected $pdo;

  public function __construct($pdo) {
    $this->pdo = $pdo;
  }

  public function getUltimosAnuncios($page, $limitePorPagina, $filtros) {
    $offset = ($page - 1) * $limitePorPagina;

    $array = [];

    $filtro_string = ['1=1'];

    if (!empty($filtros['categoria'])) {
      $filtro_string[] = 'anuncios.id_categoria = :id_categoria';
    }

    if (!empty($filtros['preco'])) {
      $filtro_string[] = 'anuncios.valor BETWEEN :preco1 AND :preco2';
    }

    if (!empty($filtros['estado'])) {
      $filtro_string[] = 'anuncios.estado = :estado';
    }

    if (!empty($filtros['pesquisa'])) {
      $filtro_string[] = 'anuncios.titulo LIKE :pesquisa';
    }


    $sql = "SELECT *, 
      (select categorias.nome from categorias 
      where anuncios.id_categoria = categorias.id)
      as categoria,
      (select anuncios_imagens.url from anuncios_imagens 
      where anuncios_imagens.id_anuncio = anuncios.id limit 1)
      as url FROM anuncios WHERE " . implode(' AND ', $filtro_string) . " ORDER BY id DESC LIMIT $offset, $limitePorPagina";

    $sql = $this->pdo->prepare($sql);

    if (!empty($filtros['categoria'])) {
      $sql->bindValue(":id_categoria", $filtros['categoria']);
    }

    if (!empty($filtros['preco'])) {
      $preco = explode('-', $filtros['preco']);
      $sql->bindValue(":preco1", $preco[0]);
      $sql->bindValue(":preco2", $preco[1]);
    }

    if (!empty($filtros['estado'])) {
      $sql->bindValue(":estado", $filtros['estado']);
    }

    if (!empty($filtros['pesquisa'])) {
      $pesquisa = "%" . $filtros['pesquisa'] . "%";
      $sql->bindValue(":pesquisa", $pesquisa);
    }

    $sql->execute();

    if ($sql->rowCount() > 0) {
      $array = $sql->fetchAll(); 
    }

    return $array;
  }

  public function getTotalAnuncios($filtros) {
    $filtro_string = ['1=1'];

    if (!empty($filtros['categoria'])) {
      $filtro_string[] = 'anuncios.id_categoria = :id_categoria';
    }

    if (!empty($filtros['preco'])) {
      $filtro_string[] = 'anuncios.valor BETWEEN :preco1 AND :preco2';
    }

    if (!empty($filtros['estado'])) {
      $filtro_string[] = 'anuncios.estado = :estado';
    }

    if (!empty($filtros['pesquisa'])) {
      $filtro_string[] = 'anuncios.titulo LIKE :pesquisa';
    }

    $sql = "SELECT COUNT(*) AS total FROM anuncios WHERE " . implode(' AND ', $filtro_string);
    $sql = $this->pdo->prepare($sql);

    if (!empty($filtros['categoria'])) {
      $sql->bindValue(":id_categoria", $filtros['categoria']);
    }

    if (!empty($filtros['preco'])) {
      $preco = explode('-', $filtros['preco']);
      $sql->bindValue(":preco1", $preco[0]);
      $sql->bindValue(":preco2", $preco[1]);
    }

    if (!empty($filtros['estado'])) {
      $sql->bindValue(":estado", $filtros['estado']);
    }

    if (!empty($filtros['pesquisa'])) {
      $pesquisa = "%" . $filtros['pesquisa'] . "%";
      $sql->bindValue(":pesquisa", $pesquisa);
    }

    $sql->execute();
    $row = $sql->fetch();
    return $row;
  }

  public function getMeusAnuncios($user_id) {
    $array = array();

    $sql = "SELECT *, 
    (SELECT anuncios_imagens.url FROM anuncios_imagens 
     WHERE anuncios_imagens.id_anuncio = anuncios.id LIMIT 1) 
     AS url 
     FROM anuncios 
     WHERE id_usuario = :id_usuario";
    $sql = $this->pdo->prepare($sql);
    $sql->bindValue(":id_usuario", $user_id);
    $sql->execute();

    if ($sql->rowCount() > 0) {
      $array = $sql->fetchAll();
    }

    return $array;
  }

  public function getTotalMeusAnuncios($user_id) {
    $sql = "SELECT COUNT(*) AS total FROM anuncios WHERE id_usuario = $user_id";
    $sql = $this->pdo->query($sql);
    return $sql->fetch();
  } 

  public function getAnuncio($id_anuncio, $id_usuario) {
    $sql = "SELECT *, 
      (SELECT categorias.nome FROM categorias 
      WHERE categorias.id = anuncios.id_categoria)
      AS categoria FROM anuncios 
      WHERE id = :id_anuncio AND id_usuario = :id_usuario";

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

  public function addAnuncio($titulo, $categoria, $valor, $descricao, $estado, $fotos) {
    $id_usuario = $_SESSION['user_id'];

    $sql = "INSERT INTO anuncios (id_usuario ,titulo, id_categoria, valor, descricao, estado)
            VALUES (:id_usuario, :titulo, :categoria, :valor, :descricao, :estado)";
    $sql = $this->pdo->prepare($sql);
    $sql->bindValue(":id_usuario", $id_usuario);
    $sql->bindValue(":titulo", $titulo);
    $sql->bindValue(":categoria", $categoria);
    $sql->bindValue(":valor", $valor);
    $sql->bindValue(":descricao", $descricao);
    $sql->bindValue(":estado", $estado);
    $sql->execute();

    $id_anuncio = $this->pdo->lastInsertId();
    $this->addFotos($fotos, $id_anuncio, $id_usuario);
  }

  public function deletarAnuncio($id_anuncio, $id_usuario) {
    // primeiro as fotos do anuncio são apagadas do servidor
    $sql = "SELECT url FROM anuncios_imagens WHERE id_anuncio = :id_anuncio";
    $sql = $this->pdo->prepare($sql);
    $sql->bindValue(":id_anuncio", $id_anuncio);
    $sql->execute();

    if ($sql->rowCount() > 0) {
      $fotos = $sql->fetchAll();
      $qt_fotos = count($fotos);
      foreach ($fotos as $foto) {
        unlink('assets/images/anuncios/' . $foto['url']);
      }
    }

    // depois os dados das fotos do anuncio, incluindo url, são apagadas do banco de dados
    $sql = "DELETE FROM anuncios_imagens WHERE id_anuncio = :id_anuncio";
    $sql = $this->pdo->prepare($sql);
    $sql->bindValue(":id_anuncio", $id_anuncio);
    $sql->execute();

    // por último o restante dos dados escrito do anuncio são excluídos
    $sql = "DELETE FROM anuncios WHERE id = :id_anuncio AND id_usuario = :id_usuario";
    $sql = $this->pdo->prepare($sql);
    $sql->bindValue(":id_anuncio", $id_anuncio);
    $sql->bindValue(":id_usuario", $id_usuario);
    $sql->execute();
  }

  public function deletarTodosAnuncios($id_usuario) {
    $anuncios = $this->getMeusAnuncios($id_usuario);
    if (count($anuncios) > 0) {
      $ids_anuncios = array();

      foreach ($anuncios as $anuncio) {
        $ids_anuncios[] = $anuncio['id'];
      }
  
      foreach ($ids_anuncios as $id_anuncio) {
        $this->deletarAnuncio($id_anuncio, $id_usuario);
      }
    }
  }

  public function editarAnuncio($titulo, $categoria, $valor, $descricao, $estado, $fotos, $id_anuncio) {
    $id_usuario = $_SESSION['user_id'];

    $sql = "UPDATE anuncios SET id_usuario = :id_usuario,
            titulo = :titulo, id_categoria = :categoria,
            valor = :valor, descricao = :descricao,
            estado = :estado WHERE id = :id_anuncio";
    $sql = $this->pdo->prepare($sql);
    $sql->bindValue(":id_usuario", $id_usuario);
    $sql->bindValue(":titulo", $titulo);
    $sql->bindValue(":categoria", $categoria);
    $sql->bindValue(":valor", $valor);
    $sql->bindValue(":descricao", $descricao);
    $sql->bindValue(":estado", $estado);
    $sql->bindValue(":id_anuncio", $id_anuncio);
    $sql->execute();

    $this->addFotos($fotos, $id_anuncio, $id_usuario);
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

      $sql = "DELETE FROM anuncios_imagens WHERE id = :id_foto AND id_usuario = :id_usuario";
      $sql = $this->pdo->prepare($sql);
      $sql->bindValue(":id_foto", $id_foto);
      $sql->bindValue(":id_usuario", $id_usuario);
      $sql->execute();
      unlink('assets/images/anuncios/' . $url);
    }

    return $id_anuncio;
  }
  
  // Método auxiliar dos métodos 'addAnuncio' e 'editarAnuncio'
  private function addFotos($fotos, $id_anuncio, $id_usuario) {
    // Primeiro um redimensionamento dessa imagem é feito...
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
          
          // ... para depois sua url e outros dados serem enviados ao banco de dados
          $sql = "INSERT INTO anuncios_imagens SET id_anuncio = :id_anuncio, id_usuario = :id_usuario, url = :url";
          $sql = $this->pdo->prepare($sql);
          $sql->bindValue(":id_anuncio", $id_anuncio);
          $sql->bindValue(":id_usuario", $id_usuario);
          $sql->bindValue(":url", $nome_foto);
          $sql->execute();
        }
      }
    }
  }

}