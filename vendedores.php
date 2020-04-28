<?php
require 'templates/header.php';

$usuarios = new Usuario($pdo);
$allUsers = $usuarios->getDadosAll();
?>

<div class="container mt-50">
  <h1 class="mb-4">Ranking de vendedores</h1>

  <table class="table table-hover shadow">
    <thead class="thead thead-light">
      <tr>
        <th>Posição no ranking</th>
        <th>Nome do usuário</th>
        <th>Email de contato</th>
        <th>Total de Anúncios</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($allUsers as $index => $user): ?>
      <tr>
        <th scope="row"># <?= $index + 1 ?></th>
        <td><?= ucfirst($user['nome']) ?></td>
        <td><?= $user['email'] ?></td>
        <td><a href="anuncios_usuario.php?id=<?= $user['id'] ?>"><?= $user['total_anuncios'] ?> (Ver anúncios)</a></td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

</div>

<?php 
require 'templates/footer.php'; 
?>