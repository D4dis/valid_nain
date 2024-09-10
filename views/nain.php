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
$groupeEdit = $_POST['groupeEdit'] ?? '';

$dsn = DB_ENGINE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

try {
  $pdo = new PDO(DSN, DB_USER, DB_PWD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

  if (isset($_POST['groupeEdit']) && !empty($_POST['groupeEdit']) && is_numeric($_POST['groupeEdit'])) {
    $requestUpdate = $pdo->prepare('UPDATE nain SET n_groupe_fk = :groupeEdit WHERE n_id = :nain_id');
    $requestUpdate->bindValue('nain_id', $nain_id);
    $requestUpdate->bindValue('groupeEdit', $groupeEdit);
    $requestUpdate->execute();
    $requestUpdate->closeCursor();
  }

  if (isset($_POST['groupeEdit']) && $_POST['groupeEdit'] == "NULL") {
    $requestUpdate = $pdo->prepare('UPDATE nain SET n_groupe_fk = NULL WHERE n_id = :nain_id');
    $requestUpdate->bindValue('nain_id', $nain_id);
    $requestUpdate->execute();
    $requestUpdate->closeCursor();
  }



  $request = $pdo->prepare('SELECT n_nom, n_barbe, v_depart.v_nom v_depart, v_depart.v_id v_departid, v_arrive.v_nom v_arrive, v_arrive.v_id v_arriveid, t_nom, taverne.t_id tav_id, g_debuttravail, g_fintravail , g_id, v_natale.v_nom v_natale, v_natale.v_id v_nataleid
                            FROM nain
                            LEFT JOIN groupe ON nain.n_groupe_fk = groupe.g_id
                            LEFT JOIN taverne ON groupe.g_taverne_fk = taverne.t_id
                            LEFT JOIN tunnel ON groupe.g_tunnel_fk = tunnel.t_id
                            LEFT JOIN ville v_depart ON tunnel.t_villedepart_fk = v_depart.v_id
                            LEFT JOIN ville v_arrive ON tunnel.t_villearrivee_fk = v_arrive.v_id
                            LEFT JOIN ville v_natale ON nain.n_ville_fk = v_natale.v_id
                            WHERE n_id = :nain_id');
  $request->bindValue('nain_id', $nain_id);
  $request->execute();
  $nain = $request->fetch(PDO::FETCH_ASSOC);
  $request->closeCursor();

  $requestGroupe = $pdo->prepare('SELECT g_id FROM groupe');
  $requestGroupe->execute();
  $groupes = $requestGroupe->fetchAll(PDO::FETCH_ASSOC);
  $requestGroupe->closeCursor();
} catch (PDOException $e) {
  die($e->getMessage());
}

asort($groupes);

var_dump($_POST);
?>

<div class="container">
  <h1 class="text-center display-2 mb-5">Nain : <?= $nain['n_nom'] ?></h1>
  <p class="text-center display-5">Taille barbe <?= $nain['n_barbe'] ?> cm</p>
  <p class="text-center display-5">Originaire de <a href="ville.php?ville=<?= $nain['v_nataleid'] ?>"><?= $nain['v_natale'] ?></a></p>
  <?php if (!empty($nain['t_nom'])) : ?>
    <p class="text-center display-5">Boit dans <a href="taverne.php?taverne=<?= $nain['tav_id'] ?>"><?= $nain['t_nom'] ?></a></p>
  <?php endif; ?>
  <?php if (!empty($nain['g_debuttravail'])) : ?>
    <p class="text-center display-5">Travail de <?= $nain['g_debuttravail'] ?> à <?= $nain['g_fintravail'] ?> dans le tunnel <a href="ville.php?ville=<?= $nain['v_departid'] ?>"><?= $nain['v_depart'] ?></a> à <a href="ville.php?ville=<?= $nain['v_arriveid'] ?>"><?= $nain['v_arrive'] ?></a></p>
  <?php endif; ?>
  <?php if (!empty($nain['g_id'])) : ?>
    <p class="text-center display-5">Membre du <a href="groupe.php?groupe=<?= $nain['g_id'] ?>">groupe n° <?= $nain['g_id'] ?></a></p>
  <?php else : ?>
    <p class="text-center display-5">Aucun groupe</p>
  <?php endif; ?>
</div>
</div>
<form class="d-flex justify-content-center" action="" method="post">
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
              <option value="<?= $groupe['g_id'] ?>" <?= $groupe['g_id'] == $nain['g_id'] ? 'selected' : '' ?>>Groupe n°<?= $groupe['g_id'] ?></option>
            <?php endforeach; ?>
            <option value="NULL">Pas de groupe</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
          <button type="submit" class="btn btn-primary">Sauvegarder</button>
        </div>
      </div>
    </div>
  </div>
</form>
</div>
</div>