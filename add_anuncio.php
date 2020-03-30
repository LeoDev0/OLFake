<?php 
ob_start();  // "output buffering" (buffer de saída) utilizado para evitar o erro 
             // "Cannot modify header information - headers already sent"
             // ao redirecionar a página com o método "header()".
require 'templates/header.php';
require 'classes/class.anuncios.php';

if (!isset($_SESSION['user_id']) && empty($_SESSION['id'])) {
  header('Location: index.php');
}

if (isset($_POST['titulo']) && !empty($_POST['titulo'])) {
  $anuncio = new Anuncios($pdo);

  $titulo = $_POST['titulo'];
  $categoria = $_POST['categoria'];
  $valor = $_POST['valor'];
  $descricao = $_POST['descricao'];
  $estado = $_POST['estado'];

  $anuncio->addAnuncio($titulo, $categoria, $valor, $descricao, $estado);
  echo "<center>";
  echo '<div class="alert alert-success">Produto adicionado com sucesso!</div>';
  echo "</center>";
}

?>

<div class="container" style="margin-top:50px;">
  <h1 style="margin-bottom:30px;">Anunciar</h1>

  <form method="post" enctype="multipart/form-data">

    <div class="form-group">
      <label for="titulo">Título</label>
      <input class="form-control" type="text" name="titulo">
    </div>

    <div class="form-group">
      <label for="categoria">Categoria</label>
      <select class="custom-select" name="categoria">
      <?php
      require 'classes/class.categorias.php';
      $categorias = new Categorias($pdo);
      $categorias = $categorias->getLista();

      foreach ($categorias as $categoria):
      ?>
        <option value="<?= $categoria['id']; ?>"><?= $categoria['nome']; ?></option>
      <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label for="valor">Valor</label>
      <input class="form-control" type="text" name="valor">
    </div>

    <div class="form-group">
      <label for="descricao">Descrição</label>
      <textarea class="form-control" type="text" name="descricao"></textarea>
    </div>

    <div class="form-group">
      <label for="estado">Estado de conservação</label>
      <select class="custom-select" name="estado">
        <option value="0">Ruim</option>
        <option value="1">Bom</option>
        <option value="2">Ótimo</option>
      </select>
    </div>

    <button class="btn btn-primary">Adicionar</button>

  </form>
  
</div>

<?php 
require 'templates/footer.php';
ob_end_flush();
?>