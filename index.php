<?php 
ob_start();
require 'templates/header.php';
require 'classes/class.anuncios.php';

$anuncios = new Anuncios($pdo);
$totalAnuncios = $anuncios->getTotalAnuncios();

$user = new Usuario($pdo);
$totalUsuarios = $user->getTotalUsuarios();

// Paginação dos anúncios
$page = 1;  # página padrão, obviamente é a 1

$limitePorPagina = 2;  # limite de carregamento de anúncios por página
$totalPaginas = ceil($totalAnuncios['total'] / $limitePorPagina);

if (isset($_GET['page']) && !empty($_GET['page'])) {
  $page = $_GET['page'];
}

$nextPage = $page + 1;
$previousPage = $page - 1;

// Voltar para a primeia página caso um usuário tente forçar acesso a uma página inexistente
if ($_GET['page'] == 0 || $page > $totalPaginas) {
  header('Location: index.php?page=1');
}

$anuncios = $anuncios->getUltimosAnuncios($page, $limitePorPagina);

// Se o usuário estiver logado, instanciar objeto da classe e pegar os dados cadastrais dele 
if (isset($_SESSION['user_id'])) {
  $user = $user->getDados($_SESSION['user_id']);
}
?>


<div class="container-fluid">

  <div style="margin-top:20px;" class="jumbotron">
    <h2>Nossa loja já possui <?= $totalAnuncios['total'] ?> anúncios e <?= $totalUsuarios['total'] ?> usuários cadastrados.</h2><br>
    <h3>O que deseja comprar<?= isset($_SESSION['user_id']) ? ", " . ucfirst($user['nome']): "";?>?</h3>
    <input class="form-control form-control-lg" type="text" placeholder="Pesquisar produto" width="200">
  </div>

  <div class="row">
    <div class="col-sm-3">
      <h4>Pesquisa Avançada</h4>
    </div>
    <div class="col-sm-9">
      <h4>Últimos anúncios</h4>
      <table class="table table-striped">
        <tbody>
        <?php foreach ($anuncios as $anuncio): ?>
          <tr>
            <td>
              <?php if (empty($anuncio['url'])): ?>
              <img height="80" src="assets/images/anuncios/default.jpg" alt="foto-anuncio">
              <?php else: ?>
              <img height="100" src="assets/images/anuncios/<?= $anuncio['url'] ?>" alt="foto-anuncio">
              <?php endif; ?>
            </td>
            <td>
              <a href="produto.php?id=<?= $anuncio['id'] ?>"><?= $anuncio['titulo'] ?></a>
              <p><?= $anuncio['categoria'] ?></p>
            </td>
            <td>R$ <?= number_format($anuncio['valor'], 2) ?></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <nav aria-label="Paginação">
    <ul class="pagination justify-content-center">

      <li class="page-item <?= ($previousPage == 0) ? 'disabled' : ''; ?>">
        <a class="page-link" href="index.php?page=<?= $previousPage ?>" aria-label="Previous">
          <span aria-hidden="true">&laquo;</span>
          <span class="sr-only">Previous</span>
        </a>
      </li>

      <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
      <li class="page-item <?= ($page == $i) ? 'active' : ''; ?>"><a class="page-link" href="index.php?page=<?= $i ?>"><?= $i ?></a></li>
      <? endfor; ?>

      <li class="page-item <?= ($nextPage > $totalPaginas) ? 'disabled' : ''; ?>">
        <a class="page-link" href="index.php?page=<?= $nextPage ?>" aria-label="Next">
          <span aria-hidden="true">&raquo;</span>
          <span class="sr-only">Next</span>
        </a>
      </li>

    </ul>
  </nav>

</div>


<?php
require 'templates/footer.php';
ob_end_flush();
?>