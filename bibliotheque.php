<?php
require 'config.php';
try {
  // Récupérer les auteurs depuis la base de données
  $stmt = $pdo->query("SELECT * FROM `auteurs`");
  $auteurs = $stmt->fetchAll(PDO::FETCH_ASSOC); // Récupérer tous les auteurs
} catch (PDOException $e) {
  die("Could not connect to the database $dbname :" . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bibliothèque</title>
  <link rel="shortcut icon" href="Images/daroul-alam.png" type="image/x-icon" />
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
  <link rel="stylesheet" href="Style.css">
  <script defer src="Javascript/script.js"></script>
</head>

<body>
  <section id="header">
    <a href="index.php"><img src="Images/logo.png" class="logo" alt="Logo" /></a>
    <div>
      <ul id="navbar">
        <li><a href="index.php">Accueil</a></li>
        <li><a href="formations.php">Formations</a></li>
        <li><a class="active" href="bibliotheque.php">Bibliothèque</a></li>
        <li><a href="propos.php">A Propos</a></li>
        <li><a href="contact.php">Contact</a></li>
        <li><a href="blog.php">Blog</a></li>
      </ul>
    </div>
    <div class="buttons">
      <a href="login/index.php"><button class="sign-up">Se connecter</button></a>
    </div>
  </section>

  <section id="page-header" class="blog-header">
    <h2>#SagessePartagée</h2>
    <p>Découvre ceux qui transmettent la lumière du savoir.</p>
    <header>
      <input type="text" id="search" placeholder="Rechercher un auteur...">
    </header>
  </section>

  <section class="auteurs" id="auteurs-list">
    <?php if (!empty($auteurs)): ?>
      <?php foreach ($auteurs as $auteur): ?>
        <div class="auteur" data-name="<?php echo htmlspecialchars($auteur['nom']); ?> <?php echo htmlspecialchars($auteur['description']); ?>">
          <a href="bibliotheque-show.php?auteur_id=<?php echo $auteur['id']; ?>">
            <img src="../admin/auteurs/uploads/<?php echo htmlspecialchars($auteur['image']); ?>" alt="<?php echo htmlspecialchars($auteur['nom']); ?>" <?php echo htmlspecialchars($auteur['description']); ?>">
            <h4><?php echo htmlspecialchars($auteur['nom']); ?></h4>
            <p><?php echo htmlspecialchars($auteur['description']); ?></p>
          </a>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>Aucun auteur trouvé.</p>
    <?php endif; ?>
  </section>

  <section id="newsletters" class="section-p1 section-m1">
    <div class="newstext">
      <h4>Inscrivez-vous Aux Newsletters</h4>
      <p>Recevez des mises à jour par e-mail sur notre dernière boutique et nos <span>offres spéciales</span>.</p>
    </div>
    <div class="form">
      <input type="text" placeholder="Votre adresse mail" />
      <button class="normal">Inscrivez-vous</button>
    </div>
  </section>

  <footer class="section-p1">
    <div class="col">
      <img class="logo" src="Images/logo.png" alt="" />
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
    <div class="col">
      <h4>À propos</h4>
      <a href="Propos.html">À propos de nous</a>
      <a href="#">Informations sur les formations</a>
      <a href="#">Politique de confidentialité</a>
      <a href="#">Termes et conditions</a>
      <a href="contact.html">Contactez-nous</a>
    </div>
    <div class="col">
      <h4>Mon compte</h4>
      <a href="#">Se connecter</a>
      <a href="Formations.html">Voir les formations</a>
      <a href="#">Ma liste de lecture</a>
      <a href="#">Suivre mes cours</a>
      <a href="#">Aide</a>
    </div>
    <div class="col install">
      <h4>Installer l'application</h4>
      <p>Depuis l'App Store ou Google Play</p>
      <div class="row">
        <img src="IMG PAYER/app.jpg" alt="" />
        <img src="IMG PAYER/play.jpg" alt="" />
      </div>
      <p>Modes de paiement sécurisées</p>
          <img src="IMG PAYER/pay.png" alt="" />
    </div>
    <div class="copyright">
      <p>&copy; <span id="year"></span> Daarul Alam. Tous droits réservés. Fait par <a class="text-green" href="https://www.linkedin.com/in/dame-seck-9ba393293/" target="_blank" rel="noopener">DAME SECK</a></p>
      <script>
        document.getElementById("year").textContent = new Date().getFullYear();
      </script>
    </div>
  </footer>

</body>

</html>
