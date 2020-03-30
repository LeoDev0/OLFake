<?php

class Categorias {
  private $pdo;

  public function __construct($pdo) {
    $this->pdo = $pdo;
  }

  public function getLista() {
    $sql = "SELECT * FROM categorias";
    $sql = $this->pdo->query($sql);

    if ($sql->rowCount() > 0) {
      $sql = $sql->fetchAll();
      return $sql; 
    }
  }
}