<?php
require 'config.php';

$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';

try {
    if (!empty($search_query)) {
        // Recherche dans la base de données
        $stmt = $pdo->prepare("SELECT * FROM formations WHERE titre LIKE :search OR description LIKE :search ORDER BY created_at DESC");
        $stmt->execute(['search' => "%$search_query%"]);
    } else {
        // Si pas de recherche, récupérer toutes les formations
        $stmt = $pdo->query("SELECT * FROM formations ORDER BY created_at DESC");
    }
    
    $formations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formations</title>
    <link rel="shortcut icon" href="Images/daroul-alam.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <section id="header">
        <a href="index.php"><img src="Images/logo.png" class="logo" alt="Logo" /></a>
        <ul id="navbar">
            <li><a href="index.php">Accueil</a></li>
            <li><a href="bibliotheque.php">Bibliothèque</a></li>
            <li><a class="active" href="formations.php">Formations</a></li>
            <li><a href="propos.php">À Propos</a></li>
            <li><a href="contact.php">Contact</a></li>
            <li><a href="blog.php">Blog</a></li>
        </ul>
        <div class="buttons">
            <a href="login/index.php"><button class="sign-up">Se connecter</button></a>
        </div>
    </section>

    <section id="page-header" class="blog-header">
        <h2>#ÉduqueTonÂme</h2>
        <p>Chaque leçon est un pas vers la lumière !</p>
        <form method="GET" action="">
            <input type="text" id="search" name="search" placeholder="Rechercher une formation..." value="<?php echo htmlspecialchars($search_query); ?>">
            <!-- <button class="btn" type="submit">Rechercher</button> -->
        </form>
    </section>

    <br><br>
    <section class="courses-section">
        <button class="btn-prev">‹</button>
        <div class="carousel-container">
            <div class="carousel-wrapper">
                <?php if ($formations): ?>
                    <?php foreach ($formations as $formation): ?>
                        <div class="course-card">
                            <h2><?php echo htmlspecialchars($formation['titre']); ?></h2>
                            <p class="description"> <?php echo htmlspecialchars($formation['description']); ?></p>
                            <a href="formation_details.php?id=<?php echo $formation['id']; ?>">
                                <button class="btn"><a href="">Voir cours</a></button>
                            </a>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>Aucune formation trouvée.</p>
                <?php endif; ?>
            </div>
        </div>
        <button class="btn-next">›</button>
    </section>

    <br><br>
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
        <!-- <p>Modes de paiement sécurisées</p>
        <img src="IMG PAYER/pay.png" alt="" /> -->
      </div>
      <div class="copyright">
        <p>
         &copy; <span id="year"></span> Daarul Alam. Tous droits réservés. Fait par
          <a
            class="text-green"
            href="https://www.linkedin.com/in/dame-seck-9ba393293/"
            target="_blank"
            rel="noopener"
            >DAME SECK</a
          >
          <script>
            document.getElementById("year").textContent = new Date().getFullYear();
          </script>
          
      </div>
    </footer>
    <script>
    document.getElementById("search").addEventListener("input", function() {
        let searchValue = this.value.trim();

        fetch("formations.php?search=" + encodeURIComponent(searchValue))
        .then(response => response.text())
        .then(html => {
            let parser = new DOMParser();
            let doc = parser.parseFromString(html, "text/html");
            let newContent = doc.querySelector(".courses-section").innerHTML;
            document.querySelector(".courses-section").innerHTML = newContent;
        });
    });
</script>


    <script src="Javascript/darul.js"></script>
</body>
</html>
