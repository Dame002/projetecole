<?php

require 'config.php';

if (isset($_GET['auteur_id'])) {
  $auteur_id = $_GET['auteur_id'];

  try {
    // Récupérer les informations de l'auteur
    $stmt = $pdo->prepare("SELECT * FROM `auteurs` WHERE `id` = :id");
    $stmt->execute(['id' => $auteur_id]);
    $auteur = $stmt->fetch(PDO::FETCH_ASSOC);

    // Récupérer les livres de l'auteur
    $stmt_livres = $pdo->prepare("SELECT * FROM `livres` WHERE `auteur_id` = :auteur_id");
    $stmt_livres->execute(['auteur_id' => $auteur_id]);
    $livres = $stmt_livres->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    die("Could not connect to the database $dbname :" . $e->getMessage());
  }
} else {
  header('Location: bibliotheque.php');
  exit();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Livres de <?php echo htmlspecialchars($auteur['nom']); ?></title>
  <link rel="shortcut icon" href="Images/daroul-alam.png" type="image/x-icon" />
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <section id="header">
    <a href="index.php"><img src="Images/logo.png" class="logo" alt="Logo" /></a>
    <div>
      <ul id="navbar">
        <li><a href="index.php">Accueil</a></li>
        <li><a href="bibliotheque.php">Bibliothèque</a></li>
        <li><a href="formations.php">Formations</a></li>
        <li><a href="propos.php">À Propos</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="blog.php">Blog</a></li>
      </ul>
    </div>
    <div class="buttons">
      <a href="login/index.php"> <button class="sign-up">Se connecter</button></a>
    </div>
  </section>

  <section id="page-header" class="blog-header">
    <h2>Livres de <?php echo htmlspecialchars($auteur['nom']); ?></h2>
    <p>Découvre les œuvres de cet auteur et enrichis tes connaissances !</p>
  </section>

  <div id="titre"><h1> Catalogue des livres</h1></div>

  <section class="courses-section">
    <button class="btn-prev">‹</button>
    <div class="carousel-container">
      <div class="carousel-wrapper">
        <?php if ($livres): ?>
          <?php foreach ($livres as $livre): ?>
            <div class="course-card">
              <h2><?php echo htmlspecialchars($livre['titre']); ?></h2>
              <p class="description">Auteur : <?php echo htmlspecialchars($auteur['nom']); ?></p>
              <a href="../admin/livres/uploads/<?php echo htmlspecialchars($livre['pdf']); ?>" target="_blank">
                <button class="btn">Télécharger le PDF</button>
              </a>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>Aucun livre disponible pour cet auteur.</p>
        <?php endif; ?>
      </div>
    </div>
    <button class="btn-next">›</button>
  </section>

  <footer class="section-p1">
    <div class="col">
      <img class="logo" src="Images/daroul.png" alt="" />
      <h4>Contacts</h4>
      <p><strong>Adresse:</strong> Rue 14, lot Oumlil Hay Hass, CASABLANCA</p>
      <p><strong>Coordonnées:</strong>+212 780003847 /+221 77 793 89 69</p>
      <p><strong>Horaires:</strong> lundi - vendredi: 9h - 22h</p>
      <div class="follow">
        <h4>Suivez-nous</h4>
        <div class="icon">
          <i class="fab fa-facebook-f"></i>
          <i class="fab fa-twitter"></i>
          <i class="fab fa-instagram"></i>
          <i class="fab fa-whatsapp"></i>
          <i class="fab fa-snapchat"></i>
        </div>
      </div>
    </div>
    <div class="copyright">
      <p>&copy; <span id="year"></span> Daarul Alam. Tous droits réservés. Fait par
        <a class="text-green" href="https://www.linkedin.com/in/dame-seck-9ba393293/" target="_blank" rel="noopener">DAME SECK</a>
      </p>
      <script>
        document.getElementById("year").textContent = new Date().getFullYear();
      </script>
    </div>
  </footer>
  <script src="Javascript/darul.js"></script>
</body>
</html>
