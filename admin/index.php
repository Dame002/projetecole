<?php
// session_start();
session_start();

require '../config.php';

try {
  // Compter le nombre de cours
  $stmtCours = $pdo->query("SELECT COUNT(*) FROM `cours`");
  $countCours = $stmtCours->fetchColumn();

  // Compter le nombre de formations
  $stmtFormations = $pdo->query("SELECT COUNT(*) FROM `formations`");
  $countFormations = $stmtFormations->fetchColumn();

  // Compter le nombre de livres
  $stmtLivres = $pdo->query("SELECT COUNT(*) FROM `livres`");
  $countLivres = $stmtLivres->fetchColumn();

  // Compter le nombre d'auteurs
  $stmtAuteurs = $pdo->query("SELECT COUNT(*) FROM `auteurs`");
  $countAuteurs = $stmtAuteurs->fetchColumn();  

  // Compter le nombre de messages
  $stmtMessages = $pdo->query("SELECT COUNT(*) FROM `messages`");
  $countMessages = $stmtMessages->fetchColumn();

  // Compter le nombre d'utilisateurs
  $stmtUtilisateurs = $pdo->query("SELECT COUNT(*) FROM `utilisateurs`");
  $countUtilisateurs = $stmtUtilisateurs->fetchColumn();
} catch (PDOException $e) {
  die("Could not connect to the database $dbname :" . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Statistiques - Daarul Alam</title>
  
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="../assets/admin.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>

<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Daarul Alam</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item"><a class="nav-link" href="./cours/add.php">Cours</a></li>
          <li class="nav-item"><a class="nav-link" href="formations/add.php">Formations</a></li>
          <li class="nav-item"><a class="nav-link" href="list.php">Livres</a></li>
          <li class="nav-item"><a class="nav-link" href="./auteurs/add.php">Auteurs</a></li>
          <li class="nav-item"><a class="nav-link" href="./Messages/liste_contact.php">Messages</a></li>
          <li class="nav-item"><a class="nav-link" href="create_admin.php">Administrateurs</a></li>
        </ul>
      </div>
    </div>
</nav>

<!-- Section des statistiques -->
<div class="container mt-4">
  <header><h3>Statistiques</h3></header> 
  <div class="row">
    <!-- Première rangée -->
    <div class="col-md-4" >
      <div class="circle">
        <i class="fas fa-book"></i>
        <?php echo $countCours; ?>
        <div class="circle-title">Cours</div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="circle">
        <i class="fas fa-graduation-cap"></i>
        <?php echo $countFormations; ?>
        <div class="circle-title">Formations</div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="circle">
        <i class="fas fa-book-open"></i>
        <?php echo $countLivres; ?>
        <div class="circle-title">Livres</div>
      </div>
    </div>
  </div>

  <div class="row">
    <!-- Deuxième rangée -->
    <div class="col-md-4">
      <div class="circle">
        <i class="fas fa-user-edit"></i>
        <?php echo $countAuteurs; ?>
        <div class="circle-title">Auteurs</div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="circle">
        <i class="fas fa-envelope"></i>
        <?php echo $countMessages; ?>
        <div class="circle-title">Messages</div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="circle">
        <i class="fas fa-users"></i>
        <?php echo $countUtilisateurs; ?>
        <div class="circle-title">Utilisateurs</div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
