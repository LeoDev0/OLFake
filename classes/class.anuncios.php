<?php

class Anuncios {
  protected $pdo;

  public function __construct($pdo) {
    $this->pdo = $pdo;
  }

  
}