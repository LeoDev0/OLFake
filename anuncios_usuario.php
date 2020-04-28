<?php 
ob_start();
require 'templates/header.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
  $id_vendedor = $_GET['id'];
  $user = new Usuario($pdo);
  $vendedor = $user->getDados($id_vendedor);

  // Se o id enviado como parâmetro não retornar dados de usuário ($vendededor == false) quando passado 
  // pelo método getDados(), significa que esse é um id inexistente e a página será redirecionada
  if ($vendedor == true) {
    $intervalo = $user->getIntervaloUltimoLogin($vendedor['data_login']);

    $a = new Anuncios($pdo);
    $anuncios = $a->getMeusAnuncios($id_vendedor);
    $total_anuncios = $a->getTotalMeusAnuncios($id_vendedor);
  } else {
    header('Location: index.php');
    exit;
  }

} else {
  header('Location: index.php');
  exit;
}
?>

<div class="container mt-50">
  <h2 class="mb-4">Página do anunciante</h2>

  <div class="breadcrumb align-items-center mb-5 shadow">
    <img style="width: 18rem; margin:10px 70px 10px 10px;" class="rounded" src="assets/images/profile-pics/<?= $vendedor['foto_perfil'] ?>" alt="profile-pic">
    <div>
      <h5 class="mb-4"><?= ucfirst($vendedor['nome']) ?></h5>
      <p>Na OLFake desde <?= strftime('%d de %B de %Y', strtotime($vendedor['data_registro'])) ?>.</p>
      <p>
      <?php
        switch ($intervalo) {
          case '0':
            echo 'Visto pela última vez hoje.';
            break;
          
          case '1':
            echo 'Visto pela última vez ontem.';
            break;

          default:
            echo "Visto pela última vez há $intervalo dias.";
            break;
        }
      ?>
      </p>
      <p>Contato: <a href="mailto:<?= $vendedor['email'] ?>"><?= $vendedor['email'] ?></a></p>
      <p>Total de anúncios: <strong><?= $total_anuncios['total'] ?></strong></p>
    </div>
  </div>

  <?php if ($total_anuncios['total'] > 0): ?>
  <h5 class="text-center mb-3">Anúncios de <?= ucfirst($vendedor['nome']) ?> (<?= $total_anuncios['total'] ?>)  </h5>
  <table class="table table-hover shadow">
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
        <a href="produto.php?id=<?= $anuncio['id'] ?>">
          <img height="80" class="rounded zoomin" src="assets/images/anuncios/default.jpg" alt="anuncio">
        </a>
        <?php else: ?>
        <a href="produto.php?id=<?= $anuncio['id'] ?>">
          <img height="100" class="rounded zoomin" src="assets/images/anuncios/<?= $anuncio['url'] ?>" alt="anuncio">
        </a>
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
  <?php else: ?>
  <h4 class="text-center"><?= ucfirst($vendedor['nome']) ?> não possui nenhum produto à venda.</h4>
  <?php endif; ?>

</div>

<?php
require 'templates/footer.php';
ob_end_flush();
?>