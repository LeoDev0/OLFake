<?php 
ob_start();  // "output buffering" (buffer de saída) utilizado para evitar o erro 
             // "Cannot modify header information - headers already sent"
             // ao redirecionar a página com o método "header()".
require 'templates/header.php';
$id = $_SESSION['user_id'];

if (!isset($_SESSION['user_id']) && empty($_SESSION['id'])) {
  header('Location: index.php');
  // echo '<script> location.replace("index.php"); </script>';
  // echo '<script> window.location.href="index.php"; </script>';
}

// exibe mensagem de confirmação de criação/deleção de anúncio caso essa $_SESSION
// tenha sido criada no arquivo 'add_anuncio.php'/'deletar_anuncio.php'
if (isset($_SESSION['confirma_add']) && !empty($_SESSION['confirma_add'])) {
  echo $_SESSION['confirma_add'];
  unset($_SESSION['confirma_add']);
}

if (isset($_SESSION['confirma_deletar']) && !empty($_SESSION['confirma_deletar'])) {
  echo $_SESSION['confirma_deletar'];
  unset($_SESSION['confirma_deletar']);
}

$a = new Anuncios($pdo);
$total_anuncios = $a->getTotalMeusAnuncios($id);
?>

<div class="container mt-50">
  <h1 class="mb-5">Meus Anúncios (<?= $total_anuncios['total'] ?>)</h1>
  <a class="btn btn-primary mb-4" href="add_anuncio.php">Novo anúncio</a>

  <?php if ($total_anuncios['total'] > 0): ?>
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
    $anuncios = $a->getMeusAnuncios($id);

    foreach ($anuncios as $anuncio):
    ?>
    <tr>
      <td>
        <?php if (empty($anuncio['url'])): ?>
        <img height="80" class="rounded" src="assets/images/anuncios/default.jpg" alt="anuncio">
        <?php else: ?>
        <img height="100" class="rounded" src="assets/images/anuncios/<?= $anuncio['url'] ?>" alt="anuncio">
        <?php endif; ?>
      </td>
      <td><?= $anuncio['titulo']; ?></td>
      <td>R$ <?= number_format($anuncio['valor'], 2) ?></td>
      <td>
        <a class="btn btn-outline-dark" href="editar_anuncio.php?id=<?= $anuncio['id']; ?>">Editar</a>
        <a class="btn btn-outline-danger" data-confirm href="deletar_anuncio.php?id=<?= $anuncio['id']; ?>">Excluir</a>
      </td>
    </tr>
    <?php endforeach; ?>

  </table>
  <?php else: ?>
  <h4 class="text-center">Você ainda não possui nenhum anúncio!</h4>
  <?php endif; ?>
</div>
<script src="assets/js/meus_anuncios.js"></script>

<?php 
require 'templates/footer.php';
ob_end_flush();
?>