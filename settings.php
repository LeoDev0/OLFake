<?php
ob_start();
require 'templates/header.php';

if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
  $id_usuario = $_SESSION['user_id'];

  $usuario = new Usuario($pdo);
  $dados = $usuario->getDados($id_usuario);
} else {
  header('Location: index.php');
}

if (isset($_FILES['submit-photo']) && !empty($_FILES['submit-photo'])) {
  $foto = $_FILES['submit-photo'];

  // Verificando se o arquivo escolhido para imagem de perfil é realmente uma 
  // imagem (apenas as extensões ".jpg", ".jpeg" e ".png" são permitidas)
  $allowedFileTypes = ['image/png', 'image/jpeg', 'image/jpg'];
  if (in_array($foto['type'], $allowedFileTypes)) {
    $usuario->changeFotoPerfil($id_usuario, $foto);

    header("Location: settings.php");
  } else {
    echo '<script language="javascript" >';
    echo 'alert("Apenas arquivos de imagem jpg/jpeg/png são permitidos!")';
    echo '</script>';
  }
}

if (isset($_POST['novo_nome']) && !empty($_POST['novo_nome'])) {
  $nome = $_POST['novo_nome'];
  $senha_antiga = $_POST['senha_antiga'];
  $nova_senha = $_POST['nova_senha'];
  $msg = $usuario->changeDados($id_usuario, $nome, $senha_antiga, $nova_senha);

  switch ($msg) {
    case 0:
      header('Location: settings.php');
      break;
    case 1:
      echo '<div class="text-center alert alert-danger">Senha incorreta!</div>';
      break;
    case 2:
      echo '<div class="text-center alert alert-success">Dados alterados com sucesso!</div>';
      break;
  }
}

if (isset($_POST['senha_deletar']) && !empty($_POST['senha_deletar'])) {
  $senha_confirmacao = md5($_POST['senha_deletar']);
  
  if ($senha_confirmacao === $dados['senha']) {
    $anuncios = new Anuncios($pdo);
    $anuncios->deletarTodosAnuncios($id_usuario);
    $usuario->deletarUsuario($id_usuario);
    
    $_SESSION['conta-deletada'] = true;
    unset($_SESSION['user_id']);
    header("Location: deletado.php");
  } else {
    echo '<script language="javascript">';
    echo 'alert("Senha incorreta! Deleção abortada.")';
    echo '</script>';
  }
}
?>

<div style="margin-top: 50px;" class="container">
  <h2 style="margin-bottom: 20px;">Meu perfil</h2>
  <div class="jumbotron">

    <div class="row">

      <div class="col-lg">
        <h3 style="margin-bottom: 20px;" class="text-center" >Alterar meus dados</h3>

        <form id="dados_form" method="post">
          <div class="form-group">
            <label for="email">Nome:</label>
            <div class="input-group">
              <input required class="form-control" type="text" name="novo_nome" value="<?= $dados['nome'] ?>">
            </div>
          </div>

          <div class="form-group">
            <label for="email">Email:</label>
            <div class="input-group">
              <input class="form-control" disabled type="email" value="<?= $dados['email'] ?>">
            </div>
          </div>

          <div class="form-group">
            <label for="email">Senha antiga:</label>
            <div class="input-group">
              <input class="form-control" type="password" name="senha_antiga">
            </div>
          </div>

          <div class="form-group">
            <label for="email">Nova senha:</label>
            <div class="input-group">
              <input id="nova_senha" class="form-control" type="password" name="nova_senha">
            </div>
          </div>

          <div class="form-group">
            <label for="email">Confirmar nova senha:</label>
            <div class="input-group">
              <input id="confirma_senha" class="form-control" type="password">
            </div>
          </div>

          <div class="text-center">
            <button style="margin-top:20px;" class="btn btn-primary" >Salvar Alterações</button>
          </div>
        </form>
      </div>

      <div class="col-lg">
        <div class="text-center">
          <img style="width: 40vh; height: 40vh; border-radius: 50%; margin: 40px 0;" src="assets/images/profile-pics/<?= $dados['foto_perfil'] ?>">
          <form id="profile-photo-form" method="post" enctype="multipart/form-data">
            <input onchange="this.form.submit()" hidden class="submit-profile-photo" type="file" name="submit-photo" accept=".png, .jpg, .jpeg">
          </form>
          <button id="change-photo-btn" class="btn btn-secondary">Trocar foto</button>
        </div>
      </div>
    </div>

    <div style="margin-top:80px;" class="text-center">
      <a href="" class="btn btn-danger" data-toggle="modal" data-target="#deletar-conta-window">Deletar conta</a>
    </div>
  </div>

</div>

<!-- Modal de deleção de conta -->
<div class="modal fade" id="deletar-conta-window">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
          <div class="alert alert-danger text-center">Tem certeza que deseja excluir sua conta? Essa decisão não pode ser revertida!</div>
            <button class="close" data-dismiss="modal"><span>&times;</span></button>
          </div>
          <div class="modal-body">
            <form method="post">
              <div class="form-group">
                <label for="senha_deletar">Confirme sua senha:</label>
                <div class="input-group">
                  <input required class="form-control" type="password" name="senha_deletar">
                </div>
              </div>
              <button class="btn btn-danger btn-block">Confirmar deleção</button>
            </form>
          </div>
        </div>
      </div>
    </div>
</div>

<?php
require 'templates/footer.php';
ob_end_flush();
?>