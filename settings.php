<?php
ob_start();
require 'templates/header.php';

if (isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])) {
  $id_usuario = $_SESSION['user_id'];

  $usuario = new Usuario($pdo);
  $usuario = $usuario->getDados($id_usuario);
} else {
  header('Location: index.php');
}

if (isset($_FILES['submit-photo']) && !empty($_FILES['submit-photo'])) {
  $foto = $_FILES['submit-photo'];

  // Verificando se o arquivo escolhido para imagem de perfil é realmente uma 
  // imagem (apenas as extensões ".jpg", ".jpeg" e ".png" são permitidas)
  $allowedFileTypes = ['image/png', 'image/jpeg', 'image/jpg'];
  if (in_array($foto['type'], $allowedFileTypes)) {
    $nomeDoArquivo = "user_id" . $id_usuario . ".jpg";
    move_uploaded_file($foto['tmp_name'], 'assets/images/profile-pics/' . $nomeDoArquivo);

    $sql = "UPDATE usuarios SET foto_perfil = :nomeDoArquivo WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":nomeDoArquivo", $nomeDoArquivo);
    $stmt->bindValue(":id", $id_usuario);
    $stmt->execute();

    header("Location: settings.php");

  } else {
    echo '<script language="javascript" >';
    echo 'alert("Apenas arquivos de imagem jpg/jpeg/png são permitidos!")';
    echo '</script>';
  }
}

?>

<div style="margin-top: 50px;" class="container">
  <h2 style="margin-bottom: 20px;">Meu perfil</h2>
  <div class="row jumbotron">

    <div class="col-lg">
      <h3 style="margin-bottom: 20px;" class="text-center" >Alterar meus dados</h3>

      <form method="post">
        <div class="form-group">
          <label for="email">Nome:</label>
          <div class="input-group">
            <input class="form-control" type="text" name="novo_nome" value="<?= $usuario['nome'] ?>">
          </div>
        </div>

        <div class="form-group">
          <label for="email">Email:</label>
          <div class="input-group">
            <input class="form-control" type="email" name="novo_email" value="<?= $usuario['email'] ?>">
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
            <input class="form-control" type="password" name="nova_senha">
          </div>
        </div>

        <div class="form-group">
          <label for="email">Confirmar nova senha:</label>
          <div class="input-group">
            <input class="form-control" type="password">
          </div>
        </div>

        <div class="text-center">
          <button style="margin-top:20px;" class="btn btn-primary" >Salvar Alterações</button>
        </div>
      </form>
    </div>

    <div class="col-lg">
      <div class="text-center">
        <img style="width: 40vh; height: 40vh; border-radius: 50%; margin: 40px 0;" src="assets/images/profile-pics/<?= $usuario['foto_perfil'] ?>">
        <form id="profile-photo-form" method="post" enctype="multipart/form-data">
          <input onchange="this.form.submit()" hidden class="submit-profile-photo" type="file" name="submit-photo" accept=".png, .jpg, .jpeg">
        </form>
        <button id="change-photo-btn" class="btn btn-secondary">Trocar foto</button>
      </div>
    </div>
  </div>


</div>


<?php
require 'templates/footer.php';
ob_end_flush();
?>