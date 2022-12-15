<?php
$db_host = "localhost";
$db_name = "telegram";
$db_user = "root";
$db_password = "";

try {
  $db = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8", $db_user, $db_password);
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
  $e->getMessage();
}

// Ajout de tache
if (isset($_POST['form']) && ($_POST['form'] == "ajout")) {

  if (isset($_POST['tache']) && !empty($_POST['tache']) && !empty($_POST['categorie'])) {
    $tache = htmlentities($_POST['tache']);
    $categorie = htmlentities($_POST['categorie']);

  $req = $db->prepare('SELECT * FROM taches WHERE nom = :nom');
  $req->execute(array('nom' => $tache));
  $donnees = $req->fetch();
  if ($donnees) {
    $msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Cette tâche existe' .
    ' déjà!!!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">'.
    '</button></div>';
    echo $msg;
  } else {
    $request = $db->prepare('INSERT INTO taches VALUES(NULL,:nom,:cat,0,NOW())');
    $request->execute(array('nom' => $tache, 'cat' => $categorie));
    $msg = '<div class="alert alert-success alert-dismissible fade show" role="alert">Ajout de la tâche'
    . ' réussie!!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">'.
    '</button></div>';
    echo $msg;
  }

  } else {
    $msg = '<div class="alert alert-danger alert-dismissible fade show" role="alert">Vous devez remplir'.
    ' tous les champs!!!<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">'
    .'</button></div>';
    echo $msg;
  }
}

if (isset($_POST['form']) && ($_POST['form'] == 'affiche')) { // Affichage des differents pane
  $categorie = htmlentities($_POST['categorie']);

  $req = $db->prepare('SELECT * FROM taches WHERE categorie = :categorie');
  $req->execute(array('categorie' => $categorie));
  $tache = array();
  echo "<table class='table table-hover text-center'>";
  while ($donnees = $req->fetch()) {
    ?>
    <tr>
      <td><?= $donnees['nom']; ?><br/><span class="text-muted text-right"><?= $donnees['creation']; ?></span></td>
      <td> <?php
      if ($donnees['termine']) {
        ?><button type="button" class="btn btn-info" disabled>Terminer</button><?php
      } else {
        ?><button type="button" page="<?= $categorie; ?>" uid="<?= $donnees['id']; ?>" class="btn btn-info fin">Terminer</button><?php
      }
              ?> <button type="button" page="<?= $categorie; ?>" uid="<?= $donnees['id']; ?>" class="btn btn-danger supp">Supprimer</button></td>
    </tr>
    <?php
  }
  echo "</table>";
}

if (isset($_POST['form']) && ($_POST['form'] == 'termine')) { // Tache terminée
  $uid = htmlentities($_POST['uid']);
  $request = $db->prepare('UPDATE taches SET termine=1 WHERE id=:uid');
  $request->execute(array('uid' => $uid));
  echo "1";

}

if (isset($_POST['form']) && ($_POST['form'] == 'supp')) { // Suppression d'une tache
  $uid = htmlentities($_POST['uid']);
  $request = $db->prepare('DELETE FROM taches WHERE id=:uid');
  $request->execute(array('uid' => $uid));
  echo "1";

}
if (isset($_POST['form']) && ($_POST['form'] == 'search')) { // Suppression d'une tache
  $search_old = htmlentities($_POST['search']);
  $search = "%$search_old%";
  $request = $db->prepare('SELECT * FROM taches WHERE nom LIKE :search');
  $request->execute(array('search' => $search));

  if ($request->rowCount() == 0)
    echo "<span class='fw-bold display-4 h2'>Aucun résultat trouvé</span>";
  else {
    echo "<table class='table table-hover text-center'>";
    echo "<thead>
      <tr><th>Tâche</th><th>Catégorie</th><th>Terminé</th><th>Date de creation</th></tr>
    </thead>";
    while ($donnees = $request->fetch()) {
      $fin = "Terminé";
      $nom = $donnees['nom'];
      $cat = $donnees['categorie'];
      $ter = $donnees['termine'];
      $creation = $donnees['creation'];
      if ($ter == 0)
        $fin = "Non terminé";
      ?>
      <tr>
        <td><?= $nom; ?></td>
        <td><?= $cat; ?></td>
        <td><?= $fin; ?></td>
        <td><?= $creation; ?></td>
      </tr>
      <?php
    }
    echo "</table>";
  }

}

