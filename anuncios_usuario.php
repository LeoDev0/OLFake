<?php 
require 'templates/header.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
  $id_vendedor = $_GET['id'];

  $a = new Anuncios($pdo);
  $anuncios = $a->getMeusAnuncios($id_vendedor);
  $total_anuncios = $a->getTotalMeusAnuncios($id_vendedor);

  $user = new Usuario($pdo);
  $vendedor = $user->getDados($id_vendedor);
} else {
  header('Location: index.php');
}

?>

<div class="container" style="margin-top:50px;">
  <h2 class="text-center" style="margin-bottom:30px;">Página do anunciante</h2>

  <div style="margin-bottom: 40px;" class="breadcrumb align-items-center">
    <img style="width: 18rem; margin:10px 70px 10px 10px;" src="assets/images/profile-pics/<?= $vendedor['foto_perfil'] ?>" alt="profile-pic">
    <div>
      <h5><?= ucfirst($vendedor['nome']) ?></h5><br>
      <p>Na OLFake desde janeiro de 2019</p>
      <p>Contato: <a href="mailto:<?= $vendedor['email'] ?>"><?= $vendedor['email'] ?></a></p>
      <p>Total de anúncios: <strong><?= $total_anuncios['total'] ?></strong></p>
    </div>
  </div>

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
      <td>R$ <?= number_format($anuncio['valor'], 2, ',', '.') ?></td>
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