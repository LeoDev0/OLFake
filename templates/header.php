<?php require 'config.php' ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/css/style.css">
  <title>Classificados</title>
</head>
<body>

  <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Navbar</a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <?php if (isset($_SESSION['logado']) && !empty($_SESSION['logado'])): ?>
        <a class="nav-item nav-link" href="#">Meus anúncios</a>
        <a class="nav-item nav-link" href="#">Sair</a>
        <?php else: ?>
        <a class="nav-item nav-link" href="" data-toggle="modal" data-target="#signup-window">Cadastre-se</a>
        <a class="nav-item nav-link" href="" data-toggle="modal" data-target="#login-window">Login</a>
        <?php endif; ?>
      </div>
    </div>
    
    <!-- Modal de login -->
    <div class="modal fade" id="login-window">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Faça login na sua conta.</h5>
            <button class="close" data-dismiss="modal"><span>&times;</span></button>
          </div>
          <div class="modal-body">
            <form>

              <div class="form-group">
                <label for="email">Email:</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">✉️</span>
                  </div>
                  <input class="form-control" type="email" name="email">
                </div>
              </div>

              <div class="form-group">
                <label for="senha">Senha:</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">🔑</span>
                  </div>
                  <input class="form-control" type="password" name="senha">
                </div>
              </div>

              <button class="btn btn-primary btn-block">Entrar</button>

            </form>
          </div>
          <div class="modal-footer">
            <button class="btn btn-danger" data-dismiss="modal">Fechar</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de signup -->
    <div class="modal fade" id="signup-window">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Cadastre-se para anunciar ou comprar já!</h5>
            <button class="close" data-dismiss="modal"><span>&times;</span></button>
          </div>
          <div class="modal-body">
            <form>

              <div class="form-group">
                <label for="email">Nome:</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">👤</span>
                  </div>
                  <input class="form-control" type="text" name="nome">
                </div>
              </div>

              <div class="form-group">
                <label for="email">Email:</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">✉️</span>
                  </div>
                  <input class="form-control" type="email" name="email">
                </div>
              </div>

              <div class="form-group">
                <label for="senha">Senha:</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">🔑</span>
                  </div>
                  <input class="form-control" type="password" name="senha">
                </div>
              </div>

              <button class="btn btn-primary btn-block">Cadastrar</button>

            </form>
          </div>
          <div class="modal-footer">
            <button class="btn btn-danger" data-dismiss="modal">Fechar</button>
          </div>
        </div>
      </div>
    </div>

  </nav>