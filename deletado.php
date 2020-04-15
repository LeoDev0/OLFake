<?php
ob_start();
require "templates/header.php";

if (isset($_SESSION['conta-deletada']) && !empty($_SESSION['conta-deletada'])) {
  unset($_SESSION['conta-deletada']);
} else {
  header('Location: index.php');
}
?>

<div style="margin-top: 50px;" class="container text-center">
  <h2 style="margin-bottom: 20px;" >Conta deletada.</h2>
  <iframe src="https://giphy.com/embed/sZJ9eVTkKgjn2" frameBorder="0" class="giphy-embed" allowFullScreen></iframe>
  <h4>Uma pena... Sentiremos sua falta.</h4><br>
  <a href="" class="nav-item nav-link" data-toggle="modal" data-target="#signup-window">Quer criar outra conta?</a>
</div>

<?php
require "templates/footer.php";
ob_end_flush();
?>