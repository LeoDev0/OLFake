<?php 
require 'templates/header.php';

// Se o usuário estiver logado, instanciar objeto da classe e pegar os dados cadastrais dele 
if (isset($_SESSION['user_id'])) {
  $user = new Usuario($pdo);
  $user = $user->getDados($_SESSION['user_id']);
}
?>


<div class="container-fluid">

  <div style="margin-top:20px;" class="jumbotron">
    <h2>O que deseja comprar<?= isset($_SESSION['user_id']) ? ", " . ucfirst($user['nome']): "";?>?</h2>
    <p>Vai lá, pesquisa aí</p>
    <input class="form-control form-control-lg" type="text" placeholder="Pesquisar produto" width="200">
  </div>

  <div class="row">
    <div class="col-sm-3">
      <h4>Pesquisa Avançada</h4>
    </div>
    <div class="col-sm-9">
      <h4>Últimos anúncios</h4>
    </div>
  </div>

</div>


<?php require 'templates/footer.php'; ?>