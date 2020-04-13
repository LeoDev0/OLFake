<?php

class Usuario {
  
  protected $pdo;

  public function __construct($pdo) {
    $this->pdo = $pdo;
  }

  public function getTotalUsuarios() {
    $sql = "SELECT COUNT(*) AS total FROM usuarios";
    $sql = $this->pdo->query($sql);
    return $sql->fetch();
  }

  public function fazerLogin($email, $senha) {
    $sql = "SELECT * FROM usuarios WHERE email = :email AND senha = :senha";
    $sql = $this->pdo->prepare($sql);
    $sql->bindValue(":email", $email);
    $sql->bindValue(":senha", $senha);
    $sql->execute();

    if ($sql->rowCount() > 0) {
      $dados = $sql->fetch();
      $_SESSION['user_id'] = $dados['id'];
      return true;
    } else {
      return false;
    }
  }

  public function registrar($nome, $email, $senha) {
    // primeiro é checado se o email já está cadastrado no banco de dados
    $sql = "SELECT id FROM usuarios WHERE email = :email";
    $sql = $this->pdo->prepare($sql);
    $sql->bindValue(":email", $email);
    $sql->execute();

    if ($sql->rowCount() == 0) {
      $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
      $sql = $this->pdo->prepare($sql);
      $sql->bindValue(":nome", $nome);
      $sql->bindValue(":email", $email);
      $sql->bindValue(":senha", $senha);
      $sql->execute();

      // faz login automático na conta após criá-la
      $id = $this->pdo->lastInsertId();
      $_SESSION['user_id'] = $id;
      return true;
    } else {
      return false;
    }
  }

  public function getDados($id) {
    $sql = "SELECT * FROM usuarios WHERE id = $id";
    $sql = $this->pdo->query($sql);
    $dados = $sql->fetch();
    return $dados;
  }

  public function getDadosAll() {
    $sql = "SELECT id, nome, email, (SELECT COUNT(id_usuario) FROM anuncios WHERE usuarios.id = anuncios.id_usuario) AS total_anuncios FROM usuarios ORDER BY total_anuncios DESC";
    $sql = $this->pdo->query($sql);
    return $sql->fetchAll();
  }

  public function changeFotoPerfil($id_usuario, $foto) {
    $nomeDoArquivo = "user_id" . $id_usuario . ".jpg";
    move_uploaded_file($foto['tmp_name'], 'assets/images/profile-pics/' . $nomeDoArquivo);

    $sql = "UPDATE usuarios SET foto_perfil = :nomeDoArquivo WHERE id = :id";
    $sql = $this->pdo->prepare($sql);
    $sql->bindValue(":nomeDoArquivo", $nomeDoArquivo);
    $sql->bindValue(":id", $id_usuario);
    $sql->execute();
  }

  public function changeDados($id_usuario, $nome, $senha_antiga, $nova_senha) {
    // Primeiro a senha do usuário é pega no banco de dados para 
    // ser enviada como parâmetro no método "changeSenha()" abaixo
    // e lá ser comparada com a digitada pelo usuário
    $sql = "SELECT senha FROM usuarios WHERE id = $id_usuario";
    $sql = $this->pdo->query($sql);
    
    if ($sql->rowCount() > 0) {
      $msg = 0; // variável para definir qual mensagem será exibida ao usuário (confirmando alteração ou exibindo erro)
      $senha_real = $sql->fetch()['senha'];
      $this->changeNome($id_usuario, $nome);

      // Se o usuário tiver optado para trocar sua senha ao digitar uma nova senha no campo 
      if (isset($nova_senha) && !empty($nova_senha) && ($nova_senha != '')) {
        $msg++;

        if ($this->changeSenha($senha_real, $id_usuario, $senha_antiga, $nova_senha)) {
          $msg++;
        }
      }
      return $msg;
    }
  }

  // Método auxiliar do método "changeDados()"
  private function changeNome($id_usuario, $nome) {
    $sql = "UPDATE usuarios SET nome = :nome WHERE id = :id";
    $sql = $this->pdo->prepare($sql);
    $sql->bindValue(":nome", $nome);
    $sql->bindValue(":id", $id_usuario);
    $sql->execute();
  }

  // Método auxiliar do método "changeDados()"
  private function changeSenha($senha_real, $id_usuario, $senha_antiga, $nova_senha) {
    $senha_antiga = md5($senha_antiga);
    $nova_senha = md5($nova_senha);
    
    // A senha do usuário retirada do banco de dados no método "changeDados()" é 
    // comparada com a senha digitada por ele. Caso esteja correta, a troca de senha será feita
    if ($senha_real == $senha_antiga) {
      $sql = "UPDATE usuarios SET senha = :senha WHERE id = :id";
      $sql = $this->pdo->prepare($sql);
      $sql->bindValue(":senha", $nova_senha);
      $sql->bindValue(":id", $id_usuario);
      $sql->execute();

      return true;
    } else {
      return false;
    }
  }

}