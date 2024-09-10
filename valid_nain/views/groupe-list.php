<?php

$title = 'Liste groupes';
require_once('../inc/head.php');
require_once('../inc/navbar.php');
require_once('../inc/foot.php');
require_once('../config/config.php');

$dsn = DB_ENGINE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

try {
  $pdo = new PDO(DSN, DB_USER, DB_PWD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

  $request = $pdo->prepare('SELECT *
                            FROM groupe');
  $request->execute();
  $groupes = $request->fetchAll(PDO::FETCH_ASSOC);
  $request->closeCursor();;
} catch (PDOException $e) {
  die($e->getMessage());
}


?>


<div class="container">
  <h1 class="text-center display-2 mb-5"><?= $title ?></h1>
  <table class="table m-auto" style="width: 20rem;">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Nom</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($groupes as $groupe) : ?>
        <tr>
          <th scope="row"><?= $groupe['g_id'] ?></th>
          <td>Groupe n°<?= $groupe['g_id'] ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>