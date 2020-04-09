<?php 
ob_start();  // "output buffering" (buffer de saída) utilizado para evitar o erro 
             // "Cannot modify header information - headers already sent"
             // ao redirecionar a página com o método "header()".
require 'templates/header.php';

if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) 
   && isset($_GET['id']) && !empty($_GET['id'])) {
  $id_usuario = $_SESSION['user_id'];
  $id_anuncio = $_GET['id'];

  $anuncio = new Anuncios($pdo);
  $info = $anuncio->getAnuncio($id_anuncio, $id_usuario);
} else {
  header('Location: index.php');
}

if (isset($_POST['titulo']) && !empty($_POST['titulo'])) {
  $titulo = $_POST['titulo'];
  $categoria = $_POST['categoria'];
  $valor = $_POST['valor'];
  $descricao = $_POST['descricao'];
  $estado = $_POST['estado'];
  $id_anuncio = $_GET['id'];
  if (isset($_FILES['fotos'])) {
    $fotos = $_FILES['fotos'];
  } else {
    $fotos = array();
  }

  $anuncio->editarAnuncio($titulo, $categoria, $valor, $descricao, $estado, $fotos, $id_anuncio);
  $info = $anuncio->getAnuncio($id_anuncio, $id_usuario); // foi necessário chamar mais uma vez o método para mostrar os dados já atualizados na página de confirmação
  echo '<div class="text-center alert alert-success">Produto editado com sucesso!</div>';
}
?>

<div class="container" style="margin-top:50px;">
  <h1 style="margin-bottom:30px;">Editar Anúncio</h1>

  <form method="post" enctype="multipart/form-data">

    <div class="form-group">
      <label for="titulo">Título do anúncio</label>
      <input class="form-control" type="text" name="titulo" value="<?= $info['titulo'] ?>">
    </div>

    <div class="form-group">
      <label for="categoria">Categoria</label>
      <select class="custom-select" name="categoria">
      <?php
      $categorias = new Categorias($pdo);
      $categorias = $categorias->getLista();

      foreach ($categorias as $categoria):
      ?>
        <option value="<?= $categoria['id']; ?>"
                <?= ($info['id_categoria'] == $categoria['id']) ? 'selected':'' ?> > 
                <?= $categoria['nome'] ?>
        </option>
      <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label for="valor">Valor</label>
      <input class="form-control" type="number" name="valor" value="<?= $info['valor'] ?>">
    </div>

    <div class="form-group">
      <label for="descricao">Descrição do produto</label>
      <textarea class="form-control" type="text" name="descricao"><?= $info['descricao'] ?></textarea>
    </div>

    <div class="form-group">
      <label for="estado">Estado de conservação</label>
      <select class="custom-select" name="estado">
        <option value="0" <?= ($info['estado'] == '0') ? 'selected':'' ?> >Ruim</option>
        <option value="1" <?= ($info['estado'] == '1') ? 'selected':'' ?> >Bom</option>
        <option value="2" <?= ($info['estado'] == '2') ? 'selected':'' ?> >Ótimo</option>
      </select>
    </div>

    <div class="form-group">
      <!-- <div class="custom-file" style="margin:30px 0 10px 0;">
        <input class="custom-file-input" id="browse" type="file" name="fotos[]" multiple accept=".png, .jpg, .jpeg" aria-describedby="inputGroupFileAddon01">
        <label id="preview" class="custom-file-label" for="browse">Adicionar fotos</label>
      </div> -->
      <div style="margin-bottom:20px;">
        <label for="fotos[]">Adicionar fotos</label><br>
        <input class="breadcrumb" style="width:100%;" type="file" name="fotos[]" multiple accept=".png, .jpg, .jpeg">
      </div>

      <div class="card">
        <div class="card-header">Fotos do produto</div>
        <div class="card-body">
          <?php foreach ($info['fotos'] as $foto): ?>
            <div class="foto_item">
              <img src="assets/images/anuncios/<?= $foto['url'] ?>" class="img-thumbnail" alt="foto_produto"><br>
              <a href="deletar_foto.php?id=<?= $foto['id'] ?>" class="btn btn-danger text-decoration-none"><i class="far fa-trash-alt"></i></a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <button class="btn btn-primary">Salvar alterações</button>
  </form>
  
</div>
<?php 
require 'templates/footer.php';
ob_end_flush();
?>