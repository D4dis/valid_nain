<?php
$title = 'Acceuil';
require_once('./inc/head.php');
require_once('./inc/foot.php');
require_once('./config/config.php');

$dsn = DB_ENGINE . ':host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

try {
  $pdo = new PDO(DSN, DB_USER, DB_PWD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

  $requestNain = $pdo->prepare('SELECT n_id,  n_nom
                            FROM nain');
  $requestNain->execute();
  $nains = $requestNain->fetchAll(PDO::FETCH_ASSOC);

  $requestVille = $pdo->prepare('SELECT v_id,  v_nom
                            FROM ville');
  $requestVille->execute();
  $villes = $requestVille->fetchAll(PDO::FETCH_ASSOC);

  $requestGroupe = $pdo->prepare('SELECT g_id FROM groupe');
  $requestGroupe->execute();
  $groupes = $requestGroupe->fetchAll(PDO::FETCH_ASSOC);

  $requestTaverne = $pdo->prepare('SELECT t_id, t_nom FROM taverne');
  $requestTaverne->execute();
  $tavernes = $requestTaverne->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die($e->getMessage());
}

asort($groupes);


?>



<div class="container">
  <h1 class="text-center display-2 mb-5">Ville de nain</h1>
  <div class="d-flex justify-content-center gap-5">
    <form action="views/nain.php" method="get">
      <div class="card" style="width: 18rem;">
        <div class="card-body">
          <h5 class="card-title text-center">Nain</h5>
          <select class="form-select form-select-sm mb-3" aria-label="Small select example" name="nain">
            <option selected disabled>Choisissez le Nain</option>
            <?php foreach ($nains as $nain) : ?>
              <option value="<?= $nain['n_id'] ?>"><?= $nain['n_nom'] ?></option>
            <?php endforeach; ?>
          </select>
          <div class="d-flex justify-content-center"><button type="sumbit" class="btn btn-primary">Y aller</button></div>
          <a class="d-flex justify-content-center mt-3 btn btn-primary" href="views/nain-list.php">Liste des nains</a>
        </div>
      </div>
    </form>

    <form action="views/ville.php" method="get">
      <div class="card" style="width: 18rem;">
        <div class="card-body">
          <h5 class="card-title text-center">Ville</h5>
          <select class="form-select form-select-sm mb-3" aria-label="Small select example" name="ville">
            <option selected disabled>Choisissez la Ville</option>
            <?php foreach ($villes as $ville) : ?>
              <option value="<?= $ville['v_id'] ?>"><?= $ville['v_nom'] ?></option>
            <?php endforeach; ?>
          </select>
          <div class="d-flex justify-content-center"><button type="sumbit" class="btn btn-primary">Y aller</button></div>
          <a class="d-flex justify-content-center mt-3 btn btn-primary" href="views/ville-list.php">Liste des villes</a>
        </div>
      </div>
    </form>

    <form action="views/groupe.php" method="get">
      <div class="card" style="width: 18rem;">
        <div class="card-body">
          <h5 class="card-title text-center">Groupe</h5>
          <select class="form-select form-select-sm mb-3" aria-label="Small select example" name="groupe">
            <option selected disabled>Choisissez le Groupe</option>
            <?php foreach ($groupes as $groupe) : ?>
              <option value="<?= $groupe['g_id'] ?>">Groupe n°<?= $groupe['g_id'] ?></option>
            <?php endforeach; ?>
          </select>
          <div class="d-flex justify-content-center"><button type="sumbit" class="btn btn-primary">Y aller</button></div>
          <a class="d-flex justify-content-center mt-3 btn btn-primary" href="views/groupe-list.php">Liste des groupes</a>
        </div>
      </div>
    </form>

    <form action="views/taverne.php" method="get">
      <div class="card" style="width: 18rem;">
        <div class="card-body">
          <h5 class="card-title text-center">Taverne</h5>
          <select class="form-select form-select-sm mb-3" aria-label="Small select example" name="taverne">
            <option selected disabled>Choisissez la Taverne</option>
            <?php foreach ($tavernes as $taverne) : ?>
              <option value="<?= $taverne['t_id'] ?>"><?= $taverne['t_nom'] ?></option>
            <?php endforeach; ?>
          </select>
          <div class="d-flex justify-content-center"><button type="sumbit" class="btn btn-primary">Y aller</button></div>
          <a class="d-flex justify-content-center mt-3 btn btn-primary" href="views/taverne-list.php">Liste des tavernes</a>
        </div>
      </div>
    </form>
  </div>
</div>