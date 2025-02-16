<?php
require 'config.php';

$search_query = '';
if (isset($_GET['search'])) {
    $search_query = $_GET['search'];
}

if (isset($_GET['auteur_id'])) {
    $auteur_id = $_GET['auteur_id'];

    try {
        // Récupérer les informations de l'auteur
        $stmt = $pdo->prepare("SELECT * FROM `auteurs` WHERE `id` = :id");
        $stmt->execute(['id' => $auteur_id]);
        $auteur = $stmt->fetch(PDO::FETCH_ASSOC);

        // Récupérer les livres de l'auteur avec ou sans recherche
        if ($search_query) {
            // Si une recherche est effectuée, filtrer par titre
            $stmt_livres = $pdo->prepare("SELECT titre, description, image, pdf FROM `livres` WHERE `auteur_id` = :auteur_id AND `titre` LIKE :search_query");
            $stmt_livres->execute([
                'auteur_id'    => $auteur_id,
                'search_query' => '%' . $search_query . '%'
            ]);
        } else {
            // Si aucune recherche n'est effectuée, récupérer tous les livres de l'auteur
            $stmt_livres = $pdo->prepare("SELECT titre, description, image, pdf FROM `livres` WHERE `auteur_id` = :auteur_id");
            $stmt_livres->execute(['auteur_id' => $auteur_id]);
        }

        $livres = $stmt_livres->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Could not connect to the database :" . $e->getMessage());
    }
} else {
    // Si l'ID de l'auteur n'est pas présent, rediriger vers la page bibliothèque
    header('Location: bibliotheque.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Livres de <?php echo htmlspecialchars($auteur['nom'] ?? 'Auteur non trouvé'); ?></title>
  <link rel="stylesheet" href="Style.css">
</head>

<body>
  <section id="header">
      <a href="index.php"><img src="Images/logo.png" class="logo" alt="Logo" /></a>
      <div>
          <ul id="navbar">
              <li><a href="index.php">Accueil</a></li>
              <li><a class="active" href="bibliotheque.php">Bibliothèque</a></li>
              <li><a href="formations.php">Formations</a></li>
              <li><a href="propos.php">À Propos</a></li>
              <li><a href="contact.php">Contact</a></li>
              <li><a href="blog.php">Blog</a></li>
          </ul>
      </div>
      <div class="buttons">
          <a href="login/index.php"><button class="sign-up">Se connecter</button></a>
      </div>
  </section>

  <section id="page-header">
      <h2>Livres de <?php echo htmlspecialchars($auteur['nom'] ?? 'Auteur non trouvé'); ?></h2>
      <form method="GET" action="">
          <header>
              <input type="text" id="search" name="search" placeholder="Rechercher un livre..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
              <button class="btn" type="submit">Rechercher</button>
          </header>
      </form>
  </section>

  <section class="livres">
      <?php if ($livres): ?>
          <?php foreach ($livres as $livre): ?>
              <div class="livre-card">
                  <!-- Vérification améliorée avec !empty() -->
                  <img src="./admin/livres/uploads/ <?php echo !empty($livre['image']) ? htmlspecialchars($livre['image']) : 'default-image.jpg'; ?>" alt="<?php echo !empty($livre['titre']) ? htmlspecialchars($livre['titre']) : 'Livre sans titre'; ?>">
                  <h3><?php echo !empty($livre['titre']) ? htmlspecialchars($livre['titre']) : 'Titre non disponible'; ?></h3>
                  <p><?php echo !empty($livre['description']) ? nl2br(htmlspecialchars($livre['description'])) : 'Description non disponible'; ?></p>
                  <a href="./admin/livres/uploads/<?php echo !empty($livre['pdf']) ? htmlspecialchars($livre['pdf']) : '#'; ?>" target="_blank">📥 Télécharger</a>
              </div>
          <?php endforeach; ?>
      <?php else: ?>
          <p>Aucun livre trouvé pour votre recherche.</p>
      <?php endif; ?>
  </section>

  <section id="newsletters" class="section-p1 section-m1">
      <div class="newstext">
          <h4>Inscrivez-vous Aux Newsletters</h4>
          <p>
              Recevez des mises à jour par e-mail sur notre dernière boutique et nos
              <span>offres spéciales</span>.
          </p>
      </div>
      <div class="form">
          <input type="text" placeholder="Votre adresse mail" />
          <button class="normal">Inscrivez-vous</button>
      </div>
  </section>

  <footer class="section-p1">
      <div class="col">
          <img class="logo" src="Images/logo.png" alt="Logo" />
          <h4>Contacts</h4>
          <p><strong>Adresse:</strong> Rue 14, lot Oumlil Hay Hass, CASABLANCA</p>
          <p><strong>Coordonnées:</strong> +212 780003847 / +221 77 793 89 69</p>
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
              <img src="IMG PAYER/app.jpg" alt="App Store" />
              <img src="IMG PAYER/play.jpg" alt="Google Play" />
          </div>
      </div>
      <div class="copyright">
          <p>
              &copy; <span id="year"></span> Daarul Alam. Tous droits réservés. Fait par
              <a class="text-green" href="https://www.linkedin.com/in/dame-seck-9ba393293/" target="_blank" rel="noopener">
                  DAME SECK</a>
          </p>
          <script>
              document.getElementById("year").textContent = new Date().getFullYear();
          </script>
      </div>
  </footer>

  <script src="Javascript/darul.js"></script>
</body>

</html>
