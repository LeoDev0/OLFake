<?php 
require 'config.php';
include 'classes/class.usuario.php';
include 'classes/class.anuncios.php';
require 'classes/class.categorias.php';

$filtros = [
  'categoria' => '',
  'preco' => '',
  'estado' => '',
  'pesquisa' => ''
];

if (isset($_GET['filtros']) && !empty($_GET['filtros'])) {
  $filtros = $_GET['filtros'];
}

$an = new Anuncios($pdo);
$totalAnuncios = $an->getTotalAnuncios($filtros);

$user = new Usuario($pdo);
$totalUsuarios = $user->getTotalUsuarios();

$cat = new Categorias($pdo);
$categorias = $cat->getLista();


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="icon" type="image/ico" href="assets/images/favicon.ico">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
  <link rel="stylesheet" href="assets/css/style.css">
  <title>OLFake</title>
</head>
<body>

  <nav class="navbar navbar-expand-sm shadow navbar-light" style="background-color: #ffc107">
    <a href="index.php">
      <img height="40px" src="assets/images/logo.png" alt="logo" title="OLFake">
    </a>

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse justify-content-end" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <form id="form-filtros" class="form-inline search-form" method="get" action="index.php">
          <div class="input-group">
            <input class="form-control" name="filtros[pesquisa]" type="search" placeholder="O que deseja comprar?" aria-label="Search" value="<?= (isset($_GET['filtros']) && !empty($_GET['filtros'])) ? $_GET['filtros']['pesquisa']: '' ?>">
            <div class="input-group-append">
              <button class="btn btn-primary"><i class="fas fa-search"></i></button>
            </div>
          </div>
        </form>
        <?php if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])): ?>
        <?php $usuario = $user->getDados($_SESSION['user_id']); ?>
        <a class="nav-item nav-link" href="meus_anuncios.php">Meus anúncios</a>
        <div class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img style="border-radius: 24px;" src="assets/images/profile-pics/<?= $usuario['foto_perfil'] ?>" height="30px" width="30px" alt="profile pic">
            <span><?= ucfirst($usuario['nome']) ?></span>
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="settings.php">Configurações</a>
            <a class="dropdown-item" href="logout.php">Sair</a>
          </div>
        </div>
        <?php else: ?>
        <a href="" class="nav-item nav-link" data-toggle="modal" data-target="#signup-window">Cadastre-se</a>
        <a href="" class="nav-item nav-link" data-toggle="modal" data-target="#login-window">Login</a>
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
            <?php 
              if (isset($_POST['email']) && !empty($_POST['email'])) {
                $email = $_POST['email'];
                $senha = md5($_POST['senha']);

                if ($user->fazerLogin($email, $senha)) {
                  header('Location: index.php');
                } else {
                  // echo '<div class="alert alert-danger">Usuário ou senha incorretos.</div>';
                  echo '<script language="javascript">';
                  echo 'alert("Usuário ou senha incorretos.")';
                  echo '</script>';
                }
              }
            ?>
            <form method="post">

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
          <div class="modal-footer justify-content-center">  
            <a href="" class="text-center" data-dismiss="modal" data-toggle="modal" data-target="#signup-window">Ainda não possui uma conta? Faça uma agora.</a>
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
            <?php
              if (isset($_POST['nome']) && !empty($_POST['nome'])) {
                $nome = $_POST['nome'];
                $email = $_POST['emailCadastro'];
                $senha = md5($_POST['senhaCadastro']);

                if (!empty($nome) && !empty($email) && !empty($senha)) {
                  if ($user->registrar($nome, $email, $senha)) {
                    header('Location: index.php');
                  } else {
                    echo '<script language="javascript">';
                    echo 'alert("Conta de email já cadastrada! Utilize outro email.")';
                    echo '</script>';
                  }
                } else {
                  echo '<script language="javascript">';
                  echo 'alert("Preencha todos os campos.")';
                  echo '</script>';   
                }
              }
            ?>
            <form method="post">

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
                  <input class="form-control" type="email" name="emailCadastro">
                </div>
              </div>

              <div class="form-group">
                <label for="senha">Senha:</label>
                <div class="input-group">
                  <div class="input-group-prepend">
                    <span class="input-group-text">🔑</span>
                  </div>
                  <input class="form-control" type="password" name="senhaCadastro">
                </div>
              </div>

              <button class="btn btn-primary btn-block">Cadastrar</button>

            </form>
          </div>

          <div class="modal-footer justify-content-center">  
            <a href="" class="text-center" data-dismiss="modal" data-toggle="modal" data-target="#login-window">Já possui conta? Faça login.</a>
          </div>

        </div>
      </div>
    </div>

  </nav>