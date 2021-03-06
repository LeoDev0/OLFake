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
    $senha = md5($senha);

    $sql = "SELECT * FROM usuarios WHERE email = :email AND senha = :senha";
    $sql = $this->pdo->prepare($sql);
    $sql->bindValue(":email", $email);
    $sql->bindValue(":senha", $senha);
    $sql->execute();

    if ($sql->rowCount() > 0) {
      $dados = $sql->fetch();
      $_SESSION['user_id'] = $dados['id'];

      // Cada vez que um login é feito com sucesso, o campo "data_login" é atualizado
      $data_atual = date('Y-m-d');
      $sql2 = $this->pdo->prepare("UPDATE usuarios SET data_login = :data_login WHERE id = :id");
      $sql2->bindValue(":data_login", $data_atual);
      $sql2->bindValue(":id", $dados['id']);
      $sql2->execute();

      return true;
    } else {
      return false;
    }
  }

  public function registrar($nome, $email, $senha) {
    $nome = filter_var($nome, FILTER_SANITIZE_SPECIAL_CHARS);
    $senha = md5($senha);

    // primeiro é checado se o email já está cadastrado no banco de dados
    $sql = "SELECT id FROM usuarios WHERE email = :email";
    $sql = $this->pdo->prepare($sql);
    $sql->bindValue(":email", $email);
    $sql->execute();

    if ($sql->rowCount() == 0) {
      $data_atual = date('Y-m-d');
      
      $sql = "INSERT INTO usuarios (nome, email, senha, data_registro, data_login) VALUES (:nome, :email, :senha, :data_registro, :data_login)";
      $sql = $this->pdo->prepare($sql);
      $sql->bindValue(":nome", $nome);
      $sql->bindValue(":email", $email);
      $sql->bindValue(":senha", $senha);
      $sql->bindValue(":data_registro", $data_atual);
      $sql->bindValue(":data_login", $data_atual);
      $sql->execute();

      // faz login automático na conta após criá-la
      $id = $this->pdo->lastInsertId();
      $_SESSION['user_id'] = $id;
      return true;
    } else {
      return false;
    }
  }

  // Vai retornar o número de dias entre a data atual e a data do último login 
  public function getIntervaloUltimoLogin($data_login) {
    $data_login = new DateTime($data_login);
    $data_atual = new DateTime(date("Y-m-d"));
    $intervalo = $data_login->diff($data_atual);
    return $intervalo->format('%a');
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
    $nome = filter_var($nome, FILTER_SANITIZE_SPECIAL_CHARS);

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

  public function deletarUsuario($id_usuario) {
    // Primeiro deletar a imagem de perfil do servidor caso ela não seja a padrão
    $dados = $this->getDados($id_usuario);
    if ($dados['foto_perfil'] !== "default-profile.png") {
      unlink("assets/images/profile-pics/" . $dados['foto_perfil']);
    }

    $sql = "DELETE FROM usuarios WHERE id = :id_usuario";
    $sql = $this->pdo->prepare($sql);
    $sql->bindValue("id_usuario", $id_usuario);
    $sql->execute();
  }

}