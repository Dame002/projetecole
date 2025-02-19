<?php
$base_url = "http://localhost:3000/"; // Remplace par l'URL de ton site
$image_path = $base_url . "admin/cours/uploads/";
$search_query = isset($_GET['search']) ? $_GET['search'] : ''; // Recherche optionnelle
require 'config.php';

if (isset($_GET['formation_id'])) {
    $formation_id = $_GET['formation_id'];
    
    try {
        // Requête pour récupérer les détails de la formation
        $stmt_formation = $pdo->prepare("SELECT * FROM formations WHERE id = :id");
        $stmt_formation->bindParam(':id', $formation_id, PDO::PARAM_INT);
        $stmt_formation->execute();
        
        $formation = $stmt_formation->fetch(PDO::FETCH_ASSOC);
        
        if (!$formation) {
            die("Formation non trouvée.");
        }

        // Optionnel: récupérer les cours associés à cette formation
        $query = "SELECT * FROM cours WHERE formation_id = :formation_id";
        
        // Si une recherche est faite, on ajoute un filtre sur le titre ou la description des cours
        if ($search_query) {
            $query .= " AND (titre LIKE :search OR description LIKE :search)";
        }

        $stmt_cours = $pdo->prepare($query);
        $stmt_cours->bindParam(':formation_id', $formation_id, PDO::PARAM_INT);
        
        // Lier le paramètre de recherche avec des jokers
        if ($search_query) {
            $search_query = "%$search_query%";
            $stmt_cours->bindParam(':search', $search_query, PDO::PARAM_STR);
        }

        $stmt_cours->execute();
        $cours = $stmt_cours->fetchAll(PDO::FETCH_ASSOC);
        
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }
} else { 
    header('Location: formations.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la formation: <?php echo htmlspecialchars($formation['titre']); ?></title>
    <link rel="shortcut icon" href="Images/daroul-alam.png" type="image/x-icon" />
    <link rel="stylesheet" href="Style.css">
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

    <!-- Page Header avec Barre de Recherche -->
    <section id="page-header">
    <?php
    // Vérification si le titre de la formation est au pluriel ou singulier
    $formation_titre = htmlspecialchars($formation['titre']);
    $is_plural = (str_ends_with($formation_titre, 's') || str_ends_with($formation_titre, 'x')); // Si le titre se termine par 's' ou 'x', c'est un pluriel
    
    // Texte du titre en fonction du nombre
    if ($is_plural) {
        echo "<h2>Cours sur les $formation_titre</h2>";
    } else {
        echo "<h2>Cours sur  $formation_titre</h2>";
    }
    ?>

    <form method="GET" action="">
        <input type="text" id="search" name="search" placeholder="Rechercher un cours..."
            value="<?php echo htmlspecialchars($search_query); ?>" onkeyup="searchCourses()">
    </form>
</section>


    <section class="cards-container">
        <?php if ($cours): ?>
            <?php foreach ($cours as $cours_item): ?>
                <div class="cours-card">
                    <img class="cours-image" src="<?php echo $base_url . "admin/cours/uploads/" . htmlspecialchars($cours_item['image']); ?>"
                    alt="<?php echo htmlspecialchars($cours_item['titre']); ?>">

                    <div class="cours-details">
                        <h3 class="cours-title"><?php echo htmlspecialchars($cours_item['titre']); ?></h3>
                        <p class="cours-description"><?php echo nl2br(htmlspecialchars($cours_item['description'])); ?></p>
                        <a href="<?php echo htmlspecialchars($cours_item['url']); ?>" class="voir-cours-btn" target="_blank">Voir le cours</a>

                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun cours trouvé pour cette formation.</p>
        <?php endif; ?>
    </section>
    <div style="text-align: center; margin-top: 20px;">
        <a href="formations.php" class="back-button">
            <span class="arrow">&#8592;</span> Retour à la liste des formations
        </a>
    </div>
    <br>

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
        <p>Modes de paiement sécurisées</p>
        <img src="IMG PAYER/pay.png" alt="" />
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

    <script src="Javascript/darul.js"></script>

    <!-- Script de recherche automatique côté client -->
    <script>
        function searchCourses() {
            var input = document.getElementById('search');
            var filter = input.value.toUpperCase();
            var courseCards = document.querySelectorAll('.cours-card'); // Sélecteur de cartes de cours

            // Filtrer les cours
            courseCards.forEach(function(card) {
                var courseTitle = card.querySelector("h3").textContent || card.querySelector("h3").innerText;
                if (courseTitle.toUpperCase().includes(filter)) {
                    card.style.display = "";  // Afficher le cours
                } else {
                    card.style.display = "none";  // Cacher le cours
                }
            });
        }
    </script>
</body>

</html>
