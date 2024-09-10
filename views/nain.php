<?php

$title = 'Nain';
require_once('../inc/head.php');
require_once('../inc/navbar.php');
require_once('../inc/foot.php');
require_once('../config/config.php');

if (isset($_GET) && empty($_GET)) {
  header('Location: ../index.php');
}

$nain_id = $_GET['nain'] ?? '';

$dsn = DB_ENGINE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

try {
  $pdo = new PDO(DSN, DB_USER, DB_PWD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

  $request = $pdo->prepare('SELECT n_nom, n_barbe, v_depart.v_nom v_depart, v_arrive.v_nom v_arrive, t_nom, g_debuttravail, g_fintravail , g_id 
                            FROM nain
                            LEFT JOIN groupe ON nain.n_groupe_fk = groupe.g_id
                            LEFT JOIN taverne ON groupe.g_taverne_fk = taverne.t_id
                            LEFT JOIN tunnel ON groupe.g_tunnel_fk = tunnel.t_id
                            LEFT JOIN ville v_depart ON tunnel.t_villedepart_fk = v_depart.v_id
                            LEFT JOIN ville v_arrive ON tunnel.t_villearrivee_fk = v_arrive.v_id
                            WHERE n_id = :nain_id');
  $request->bindValue('nain_id', $nain_id);
  $request->execute();
  $nain = $request->fetch(PDO::FETCH_ASSOC);
  $request->closeCursor();

  $requestGroupe = $pdo->prepare('SELECT g_id FROM groupe');
  $requestGroupe->execute();
  $groupes = $requestGroupe->fetchAll(PDO::FETCH_ASSOC);
  $request->closeCursor();
} catch (PDOException $e) {
  die($e->getMessage());
}

asort($groupes);


?>

<div class="container">
  <h1 class="text-center display-2 mb-5">Nain : <?= $nain['n_nom'] ?></h1>
  <div class="d-flex flex-column align-items-center gap-5">
    <div class="card border-0 shadow-sm" style="width: 20rem;">
      <div class="card-body">
        <p class="card-title">Taille barbe <?= $nain['n_barbe'] ?> cm</p>
        <?php if (!empty($nain['t_nom'])) : ?>
          <p class="card-title">Boit dans <?= $nain['t_nom'] ?></p>
        <?php endif; ?>
        <?php if (!empty($nain['g_debuttravail'])) : ?>
          <p class="card-title">Travail de <?= $nain['g_debuttravail'] ?> à <?= $nain['g_fintravail'] ?> dans le tunnel <?= $nain['v_depart'] ?> à <?= $nain['v_arrive'] ?></p>
        <?php endif; ?>
        <p class="card-title"><?= !empty($nain['g_id']) ? "Membre du groupe n° " . $nain['g_id'] : 'Aucun groupe' ?></p>
      </div>
    </div>
    <form action="" method="post">
      <!-- Button trigger modal -->
      <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
        Changer de groupe
      </button>

      <!-- Modal -->
      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Changer le groupe</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <select class="form-select form-select-sm mb-3" aria-label="Small select example" name="groupeEdit">
                <option selected disabled>Changer le groupe</option>
                <?php foreach ($groupes as $groupe) : ?>
                  <option value="<?= $groupe['g_id'] ?>"><?= $groupe['g_id'] ?></option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermez</button>
              <button type="submit" class="btn btn-primary">Sauvegarder</button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>