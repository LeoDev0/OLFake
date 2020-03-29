<?php

class Usuario {
  
  protected $pdo;

  public function __construct($pdo) {
    $this->pdo = $pdo;
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
}