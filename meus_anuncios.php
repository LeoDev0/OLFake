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

// exibe mensagem de confirmação de criação de anúncio caso essa $_SESSION
// tenha sido criada no arquivo 'add_anuncio.php'
if (isset($_SESSION['confirma_add']) && !empty($_SESSION['confirma_add'])) {
  echo $_SESSION['confirma_add'];
  unset($_SESSION['confirma_add']);
}
?>

<div class="container" style="margin-top:50px;">
  <h1 style="margin-bottom:30px;">Meus Anúncios</h1>
  <a style="margin-bottom:30px;" class="btn btn-primary" href="add_anuncio.php">Novo anúncio</a>

  <table class="table table-hover">
    <thead class="thead thead-light">
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
    $anuncios = $anuncios->getMeusAnuncios();

    foreach ($anuncios as $anuncio):
    ?>
    <tr>
      <td>
        <?php if (empty($anuncio['url'])): ?>
        <img height="80" src="assets/images/anuncios/default.jpg" alt="anuncio">
        <?php else: ?>
        <img height="100" src="assets/images/anuncios/<?= $anuncio['url'] ?>" alt="anuncio">
        <?php endif; ?>
      </td>
      <td><?= $anuncio['titulo']; ?></td>
      <td>R$ <?= number_format($anuncio['valor'], 2) ?></td>
      <td>
        <a class="btn btn-outline-dark" href="editar_anuncio.php?id=<?= $anuncio['id']; ?>">Editar</a>
        <a class="btn btn-outline-danger" href="deletar_anuncio.php?id=<?= $anuncio['id']; ?>">Excluir</a>
      </td>
    </tr>
    <?php endforeach; ?>

  </table>
</div>


<?php 
require 'templates/footer.php';
ob_end_flush();
?>