<?php
session_start();


// CONNEXION A LA BDD
$host = "localhost";
$dbname = "bdd1";
$username = "root";
$password = "";
$dbConnect = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$dbConnect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


$sqlSelect = "SELECT * FROM `film`";
$stmtSelect = $dbConnect->prepare($sqlSelect);
$stmtSelect->execute();
$result = $stmtSelect->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
</head>
<body class="body">
    <header class="headd">
        <img class='imgheadd' src="image/marvel.jpg" alt="">
        <section class="button">
            <form method="POST" action="">
                <input type="text" name="identifiant" placeholder="votre identifiant">
                <input type="password" name="password" placeholder="votre password">
        
                <?php
                if (isset($_SESSION['data'])) {
                    // Utilisateur connecté
                    echo '<input type="submit" name="deconnecter" value="Déconnexion">';
                } else {
                    // Utilisateur non connecté
                    echo '<input type="submit" name="connecter" value="Connexion"><br> <br>';
                }
                ?>

                <!-- formulaire session vide -->
                <?php
                if (empty($_SESSION)) {
                    echo '<form method="POST" action="">
                    <input type="text" name="email" placeholder="votre adresse email">
                    <input type="text" name="nouvel_identifiant" placeholder="Nouvel identifiant"> 
                    <input type="password" name="nouveau_password" placeholder="Nouveau mot de passe">
                    <input type="submit" name="sinscrire" value="Sinscrire"> 
                </form>';
                }
                ?>
            </form>
        </section>
    </header> <br> <br>

    <?php
    // message connexion 
    if (isset($_POST['connecter']) && ($_POST['identifiant'] == 'jasonbch' && $_POST['password'] == '29082003')) {
        $_SESSION['data'] = [
            'identifiant' => 'jasonbch',
            'password' => '29082003'
        ];
        echo " <div class='message message-success'>Bienvenue, vous êtes connecté.</div><br>";
    }

    // Code to handle logout
    if (isset($_POST['deconnecter']) && isset($_SESSION['data'])) {
        session_destroy();
        echo '<form method="POST"></form>';
    } elseif (isset($_POST['password']) && ($_POST['identifiant'] != 'jasonbch' || $_POST['password'] != '29082003' || empty($_SESSION))) {
        echo "<div class='message message-error'>Nom d'utilisateur ou mot de passe incorrect.</div>";
    }
    ?>

    <?php
    if (!empty($_SESSION)) {
        echo '<div class="container">
                <div class="row">'; // Open container and row outside the loop
                foreach ($result as $film) {
            echo '<div class="col-4">
                    <div class="card">
                      <div class="card-body">
                        <h5 class="card-title">' . $film['titre'] . '</h5>
                        <img src="' . $film['affiche'] . '" class="card-img-top" alt="Image du film">
                        <p class="card-text">' . $film['durée'] . '</p>
                        <p class="card-text">' . $film['date'] . '</p>
                        </div>
                    </div>
                  </div>';
        }

       
    }
    ?>
    <br> <br> 

    <!-- confirmation d'inscription  -->
    <?php
    if (isset($_POST['sinscrire'])) {
        $nouvelIdentifiant = $_POST['nouvel_identifiant'];
        $nouveauPassword = $_POST['nouveau_password'];

        // Code pour insérer les données d'inscription dans la base de données
        $sqlInsert = "INSERT INTO `utilisateur` (`pseudo`, `mail`, `password`) VALUES (:pseudo, :mail, :password)";
        $stmtInsert = $dbConnect->prepare($sqlInsert);
        $stmtInsert->execute(array(
            ':pseudo' => $nouvelIdentifiant,
            ':mail' => $_POST['email'],
            ':password' => $nouveauPassword
        ));

        echo "<div class='message message-success'>Inscription réussie !</div>";
    }
     ?>

<!-- //  formulaire ajouter des films -->
  <?php
  if (empty($_SESSION)) {
      echo '<div class="ajout">
              <h1><b>Ajouter un film</b></h1>
              <form action="" method="post">
                  <label for="titre"><b>Titre du film</b></label>
                  <input type="text" id="titre" name="titre" required><br>
          
                  <label for="realisateur"><b>Réalisateur</b></label>
                  <input type="text" id="realisateur" name="realisateur" required><br>
          
                  <label for="annee_sortie"><b>Année de sortie</b></label>
                  <input type="number" id="annee_sortie" name="annee_sortie" required><br>
          
                  <input type="submit" name="ajouter" value="Ajouter">
              </form>
          </div>';
  }

  // Requête pour insérer le film dans la table "films"
  if (isset($_POST['ajouter'])) {
      $titre = $_POST['titre'];
      $nom_realisateur = $_POST['nom_realisateur'];
      $date = $_POST['date'];
      echo "<div class='message message-success>Film ajouté avec succès ! </div>";
  }

      $sql = "INSERT INTO `film` (`titre`, `nom_realisateur`, `date`) VALUES ('$titre', '$realisateur', $annee_sortie)";
      $stmt = $dbConnect->prepare($sql)
?>
<!--   echo "<div class='message message-success>Film ajouté avec succès ! </div>"; -->
    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js' integrity='sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz' crossorigin='anonymous'></script>;
</body>
</html>