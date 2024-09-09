<?php

$title = 'Nain';
require_once('../inc/head.php');
require_once('../inc/navbar.php');
require_once('../inc/foot.php');
require_once('../config/config.php');

if (isset($_POST) && empty($_POST)) {
  header('Location: ../index.php');
}

$nain_id = $_POST['nain'] ?? '';

$dsn = DB_ENGINE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

try {
  $pdo = new PDO(DSN, DB_USER, DB_PWD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

  $request = $pdo->prepare('SELECT n_nom, n_barbe, v_nom, t_nom, g_debuttravail, g_fintravail , g_id , t_villedepart_fk, t_villearrivee_fk
                            FROM nain
                            JOIN ville ON nain.n_ville_fk = ville.v_id
                            JOIN groupe ON nain.n_groupe_fk = groupe.g_id
                            JOIN taverne ON groupe.g_taverne_fk = taverne.t_id
                            JOIN tunnel ON ville.v_id = tunnel.t_villedepart_fk
                            WHERE n_id = :nain_id');
  $request->bindValue('nain_id', $nain_id);
  $request->execute();
  $nain = $request->fetch(PDO::FETCH_ASSOC);
  $request->closeCursor();
} catch (PDOException $e) {
  die($e->getMessage());
}

var_dump($nain);

?>

<div class="container">
  <h1 class="text-center display-2 mb-5">Nain : <?= $nain['n_nom'] ?></h1>
  <div class="d-flex justify-content-center">
    <div class="card border-0 shadow-sm" style="width: 20rem;">
      <div class="card-body">
        <p class="card-title">Taille barbe <?= $nain['n_barbe'] ?> cm</p>
        <p class="card-title">Boit dans <?= $nain['t_nom'] ?></p>
        <p class="card-title">Travail de <?= $nain['g_debuttravail'] ?> à <?= $nain['g_fintravail'] ?> dans le tunnel <?= $nain['t_villedepart_fk'] ?> à <?= $nain['t_villearrivee_fk'] ?></p>
        <p class="card-title">Membre du groupe n° <?= $nain['g_id'] ?></p>
      </div>
    </div>
  </div>
</div>

