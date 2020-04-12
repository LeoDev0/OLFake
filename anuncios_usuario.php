<?php 
require 'templates/header.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
  $id_vendedor = $_GET['id'];

  $anuncios = new Anuncios($pdo);
  $anuncios = $anuncios->getMeusAnuncios($id_vendedor);

  $user = new Usuario($pdo);
  $vendedor = $user->getDados($id_vendedor);
} else {
  header('Location: index.php');
}

?>

<div class="container" style="margin-top:50px;">
  <h2 style="margin-bottom:30px;">Anúncios de: <?= ucfirst($vendedor['nome']) ?></h2>

  <table class="table table-hover">
    <thead class="thead thead-light">
      <tr>
        <th>Foto</th>
        <th>Título</th>
        <th>Valor</th>
        <th>Ações</th>
      </tr>
    </thead>
    <?php foreach ($anuncios as $anuncio): ?>
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
        <a href="produto.php?id=<?= $anuncio['id'] ?>">Acessar produto</a>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>

</div>

<?php
require 'templates/footer.php';
?>