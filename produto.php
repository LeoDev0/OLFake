<?php
require 'templates/header.php';
// require 'classes/class.anuncios.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
  $id_anuncio = $_GET['id'];
  $anuncio = new Anuncios($pdo);

  $sql = $pdo->query("SELECT id_usuario FROM anuncios WHERE id = $id_anuncio");
  if ($sql->rowCount() > 0) {
    $id_usuario = $sql->fetch()['id_usuario'];

    $anuncio = $anuncio->getAnuncio($id_anuncio, $id_usuario);
  } else {
    header('Location: index.php');  
  }
} else {
  header('Location: index.php');
}
?>

<div class="container-fluid">
  <div class="row" style="margin:20px 0;">
    <div class="col-sm-5">

      <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
        <?php foreach ($anuncio['fotos'] as $chave => $foto): ?>
          <li data-target="#carouselExampleIndicators" data-slide-to="<?= $chave ?>" <?= ($chave == 0) ? 'class="active"': '' ?>></li>
        <?php endforeach; ?>
        </ol>
        <div class="carousel-inner">
        <?php foreach ($anuncio['fotos'] as $chave => $foto): ?>
          <div class="carousel-item <?= ($chave == 0) ? 'active': '' ?>">
            <img class="d-block w-100" src="assets/images/anuncios/<?= $foto['url'] ?>" alt="slide <?= $chave + 1 ?>">
          </div>
        <?php endforeach; ?>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="sr-only">Next</span>
        </a>
      </div>

    </div>
    <div class="col-sm-7">
      <h1><?= $anuncio['titulo'] ?></h1>
      <h4><?= $anuncio['categoria'] ?></h4>
      <p><?= $anuncio['descricao'] ?></p>
      <br>
      <h3>R$ <?= number_format($anuncio['valor'], 2) ?></h3>
    </div>
  </div>
</div>


<?php require 'templates/footer.php'; ?>