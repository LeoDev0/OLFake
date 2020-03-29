<?php 
ob_start();  // "output buffering" (buffer de saída) utilizado para evitar o erro 
             // "Cannot modify header information - headers already sent"
             // ao redirecionar a página com o método "header()".
require 'templates/header.php';

if (!isset($_SESSION['user_id']) && empty($_SESSION['id'])) {
  header('Location: index.php');
  // echo '<script> location.replace("index.php"); </script>';
  // echo '<script> window.location.href="index.php"; </script>';
}
?>

<div class="container" style="margin-top:50px;">
  <h1 style="margin-bottom:30px;">Meus Anúncios</h1>
  <a style="margin-bottom:30px;" class="btn btn-primary" href="add_anuncio.php">Adicionar anúncio</a>

  <table class="table table-striped">
    <thead>
      <tr>
        <th>Foto</th>
        <th>Título</th>
        <th>Valor</th>
        <th>Ações</th>
      </tr>
    </thead>
    <?php
    include 'classes/class.anuncios.php';
    $anuncios = new Anuncios($pdo);
    // $anunciois = 
    foreach ($anuncios as $anuncio):
    ?>
    <tr>
      <td><img src="assets/images/anuncios/<?= $anuncio['url'] ?>" alt="anuncio"></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <?php endforeach; ?>

  </table>
</div>


<?php 
require 'templates/footer.php';
ob_end_flush();
?>