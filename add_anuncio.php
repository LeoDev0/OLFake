<?php 
ob_start();  // "output buffering" (buffer de saída) utilizado para evitar o erro 
             // "Cannot modify header information - headers already sent"
             // ao redirecionar a página com o método "header()".
require 'templates/header.php';

if (!isset($_SESSION['user_id']) && empty($_SESSION['id'])) {
  header('Location: index.php');
}

if (isset($_POST['titulo']) && !empty(trim($_POST['titulo'])) && !empty($_POST['valor'])) {
  $anuncio = new Anuncios($pdo);

  $titulo = trim($_POST['titulo']);
  $categoria = $_POST['categoria'];
  $valor = $_POST['valor'];
  $descricao = trim($_POST['descricao']);
  $estado = $_POST['estado'];
  if (isset($_FILES['fotos'])) {
    $fotos = $_FILES['fotos'];
  } else {
    $fotos = array();
  }

  $anuncio->addAnuncio($titulo, $categoria, $valor, $descricao, $estado, $fotos);
  $_SESSION['confirma_add'] = '<div class="text-center alert alert-success">Produto adicionado com sucesso!</div>';
  header('Location: meus_anuncios.php');
}

?>

<div class="container mt-50">
  <h1 class="mb-5">Anunciar</h1>

  <form class="mb-4" method="post" enctype="multipart/form-data">
    <div class="form-group">
      <label for="titulo">Título do anúncio <span class="text-danger"><strong>*</strong></span></label>
      <input class="form-control" type="text" name="titulo" required>
    </div>

    <div class="row">
      
      <div class="col-lg-4">
        <div class="form-group">
          <label for="valor">Valor <span class="text-danger"><strong>*</strong></span></label>
          <input class="form-control" type="number" name="valor" min='0' required>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="form-group">
          <label for="categoria">Categoria</label>
          <select class="custom-select" name="categoria">
          <?php
          $categorias = new Categorias($pdo);
          $categorias = $categorias->getLista();

          foreach ($categorias as $categoria):
          ?>
            <option value="<?= $categoria['id']; ?>"><?= $categoria['nome']; ?></option>
          <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="col-lg-4">
        <div class="form-group">
          <label for="estado">Estado de conservação</label>
          <select class="custom-select" name="estado">
            <option value="0">Ruim</option>
            <option value="1">Bom</option>
            <option value="2">Ótimo</option>
          </select>
        </div>
      </div>

    </div>
    <div class="form-group">
      <label for="descricao">Descrição do produto</label>
      <textarea class="form-control" type="text" name="descricao"></textarea>
    </div>

    <div class="mb-4">
      <label for="fotos[]">Adicionar fotos</label><br>
      <input class="breadcrumb" style="width:100%;" type="file" name="fotos[]" multiple accept=".png, .jpg, .jpeg">
    </div>

    <button class="btn btn-primary">Adicionar produto</button>
  </form>
  
</div>

<?php 
require 'templates/footer.php';
ob_end_flush();
?>