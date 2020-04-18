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

    $usuario = new Usuario($pdo);
    $vendedor = $usuario->getDados($id_usuario);
  } else {
    header('Location: index.php');  
  }
} else {
  header('Location: index.php');
}
?>

<div class="container-fluid" style="margin-top: 50px;">
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
      <h4>Categoria: <?= $anuncio['categoria'] ?></h4>
      <p><?= $anuncio['descricao'] ?></p>
      <p>Vendedor: <a href="anuncios_usuario.php?id=<?= $id_usuario?>"><?= ucfirst($vendedor['nome']) ?></a></p>
      <br>
      <h3>R$ <?= number_format($anuncio['valor'], 2, ',', '.') ?></h3>
      <br>
      <button 
        class="btn btn-lg btn-success"
        data-toggle="modal"
        <?= isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) ? "data-target='#offer-window'": "data-target='#login-window'" ?>>
        Fazer oferta</button>
    </div>
  </div>
</div>

<!-- Modal botão de fazer oferta -->
<div class="modal fade" id="offer-window">

      <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
          <div class="modal-header" style="background-color: #ffc107">
            <h5 class="modal-title">Comprar <?= $anuncio['titulo'] ?></h5>
            <button class="close" data-dismiss="modal"><span>&times;</span></button>
          </div>
          <div class="modal-body">

            <div class="form-group">
              <label for="email">Valor da oferta:</label>
              <div class="input-group">
                <input class="form-control" type="number" value="<?= $anuncio['valor'] ?>">
              </div>
            </div>

            <div class="form-group">
              <label for="email">Mensagem para o vendedor:</label>
              <div class="input-group">
                <textarea class="form-control" cols="30" rows="5" placeholder="Escreva uma mensagem ao vendedor falando sobre seu interesse no produto e adicionando observações sobre seu pedido (cor, tamanho, tipo, forma de pagamento etc.)"></textarea>
              </div>
            </div>

            <button class="btn btn-block btn-success">Enviar oferta</button>
          </div>
        </div>
      </div>
    </div>

<?php require 'templates/footer.php'; ?>