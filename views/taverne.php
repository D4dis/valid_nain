<?php

$title = 'Taverne';
require_once('../inc/head.php');
require_once('../inc/navbar.php');
require_once('../inc/foot.php');
require_once('../config/config.php');

if (isset($_GET) && empty($_GET)) {
  header('Location: ../index.php');
}

$taverne_id = $_GET['taverne'] ?? '';

$dsn = DB_ENGINE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

try {
  $pdo = new PDO(DSN, DB_USER, DB_PWD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

  $request = $pdo->prepare('SELECT t_id, t_nom, v_nom, v_id, t_blonde, t_brune, t_rousse, t_id, t_chambres
                            FROM taverne
                            JOIN ville ON ville.v_id = taverne.t_ville_fk
                            WHERE t_id = :taverne_id');
  $request->bindValue('taverne_id', $taverne_id);
  $request->execute();
  $taverne = $request->fetch(PDO::FETCH_ASSOC);
  $request->closeCursor();
} catch (PDOException $e) {
  die($e->getMessage());
}


?>

<div class="container">
  <h1 class="text-center display-2">Taverne : <?= $taverne['t_nom'] ?></h1>
  <p class="text-center display-5">Ville : <a href="ville.php?ville=<?= $taverne['v_id'] ?>"><?= $taverne['v_nom'] ?></a></p>
  <p class="text-center display-5">Possède de la bière <?= $taverne['t_blonde'] == 1 ? 'blonde' : '' ?><?= $taverne['t_brune'] == 1 ? ', brune' : '' ?><?= $taverne['t_rousse'] == 1 ? ', rousse' : '' ?></p>
  <p class="text-center display-5"><?= $taverne['t_chambres'] ?> chambres, dont X libres</p>
</div>