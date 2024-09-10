<?php

$title = 'Ville';
require_once('../inc/head.php');
require_once('../inc/navbar.php');
require_once('../inc/foot.php');
require_once('../config/config.php');

if (isset($_GET) && empty($_GET)) {
  header('Location: ../index.php');
}

$ville_id = $_GET['ville'] ?? '';

$dsn = DB_ENGINE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

try {
  $pdo = new PDO(DSN, DB_USER, DB_PWD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

  $request = $pdo->prepare('SELECT v_id, v_nom, v_superficie, t_id
                            FROM ville
                            JOIN taverne ON ville.v_id = taverne.t_ville_fk
                            WHERE v_id = :ville_id');
  $request->bindValue('ville_id', $ville_id);
  $request->execute();
  $ville = $request->fetch(PDO::FETCH_ASSOC);
  $request->closeCursor();

  $requestNain = $pdo->prepare('SELECT n_nom, v_nom
                            FROM nain
                            JOIN ville ON nain.n_ville_fk = ville.v_id
                            WHERE n_ville_fk = :ville_id');
  $requestNain->bindValue('ville_id', $ville_id);
  $requestNain->execute();
  $nains = $requestNain->fetchAll(PDO::FETCH_ASSOC);
  $requestNain->closeCursor();

  $requestTaverne = $pdo->prepare('SELECT t_nom
                            FROM taverne
                            JOIN ville ON taverne.t_ville_fk = ville.v_id
                            WHERE t_ville_fk = :ville_id');
  $requestTaverne->bindValue('ville_id', $ville_id);
  $requestTaverne->execute();
  $tavernes = $requestTaverne->fetchAll(PDO::FETCH_ASSOC);
  $requestTaverne->closeCursor();

  $requestTunnel = $pdo->prepare('SELECT v_arrive.v_nom v_arrive, v_arrive.v_id v_id, t_progres
                            FROM tunnel
                            LEFT JOIN ville v_arrive ON tunnel.t_villearrivee_fk = v_arrive.v_id
                            JOIN ville v_depart ON tunnel.t_villedepart_fk = v_depart.v_id
                            WHERE v_depart.v_id = :ville_id');
  $requestTunnel->bindValue('ville_id', $ville_id);
  $requestTunnel->execute();
  $tunnels = $requestTunnel->fetchAll(PDO::FETCH_ASSOC);
  $requestTunnel->closeCursor();
} catch (PDOException $e) {
  die($e->getMessage());
}


?>

<div class="container">
  <h1 class="text-center display-2">Ville : <?= $ville['v_nom'] ?></h1>
  <p class="text-center display-5">Superficie : <?= $ville['v_superficie'] ?> km²</p>

  <!-- Button trigger modal -->
  <div class="d-flex justify-content-center mb-3">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
      Liste nains originaires de cette ville
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

  <!-- Button trigger modal -->
  <div class="d-flex justify-content-center">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal2">
      Liste tavernes de la ville
    </button>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="exampleModalLabel">Tavernes villes</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <?php foreach ($tavernes as $taverne) : ?>
          <div class="modal-body">
            <?= $taverne['t_nom'] ?>
          </div>
        <?php endforeach; ?>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
        </div>
      </div>
    </div>
  </div>
  <?php foreach ($tunnels as $tunnel) : ?>
    <p class="text-center display-5">Tunnel vers <a href="ville.php?ville=<?= $tunnel['v_id'] ?>"><?= $tunnel['v_arrive'] ?></a> : <?= $tunnel['t_progres'] == 100 ? 'Ouvert' : $tunnel['t_progres'] . ' %' ?> </p>
  <?php endforeach; ?>
</div>