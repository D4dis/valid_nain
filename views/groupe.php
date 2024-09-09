<?php

$title = 'Groupe';
require_once('../inc/head.php');
require_once('../inc/navbar.php');
require_once('../inc/foot.php');
require_once('../config/config.php');

if (isset($_GET) && empty($_GET)) {
  header('Location: ../index.php');
}

$groupe_id = $_GET['groupe'] ?? '';

$dsn = DB_ENGINE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

try {
  $pdo = new PDO(DSN, DB_USER, DB_PWD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

  $request = $pdo->prepare('SELECT g_id, t_nom 
                            FROM groupe
                            LEFT JOIN taverne ON taverne.t_id = groupe.g_taverne_fk
                            WHERE g_id = :groupe_id');
  $request->bindValue('groupe_id', $groupe_id);
  $request->execute();
  $groupe = $request->fetch(PDO::FETCH_ASSOC);
  $request->closeCursor();

  $requestNain = $pdo->prepare('SELECT n_nom
                                FROM nain
                                WHERE n_groupe_fk = :groupe_id');
  $requestNain->bindValue('groupe_id', $groupe_id);
  $requestNain->execute();
  $nains = $requestNain->fetchAll(PDO::FETCH_ASSOC);
  $requestNain->closeCursor();
} catch (PDOException $e) {
  die($e->getMessage());
}

var_dump($_POST);

?>

<div class="container">
  <h1 class="text-center display-2 mb-5">Groupe n°<?= $groupe['g_id'] ?></h1>
  <!-- Button trigger modal -->
  <div class="d-flex justify-content-center mb-3">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
      Liste nains du groupe n°<?= $groupe['g_id'] ?>
    </button>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Noms nains</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <?php foreach ($nains as $nain) : ?>
          <div class="modal-body">
            <?= $nain['n_nom'] ?>
          </div>
        <?php endforeach; ?>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>
  <p class="text-center display-5">Boivent dans <?= !empty($groupe['t_nom']) ? $groupe['t_nom'] : 'aucune' ?></p>

</div>