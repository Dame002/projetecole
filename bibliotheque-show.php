<?php
require 'config.php';
$base_url = "http://localhost:3000/"; // Remplace par l'URL de ton site
$image_path = $base_url . "admin/livres/uploads/";
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

if (isset($_GET['auteur_id'])) {
    $auteur_id = $_GET['auteur_id'];

    try {
        // Récupérer les informations de l'auteur
        $stmt = $pdo->prepare("SELECT * FROM `auteurs` WHERE `id` = :id");
        $stmt->bindParam(':id', $auteur_id, PDO::PARAM_INT);
        $stmt->execute();
        $auteur = $stmt->fetch(PDO::FETCH_ASSOC);

        // Récupérer les livres de l'auteur avec ou sans recherche
        if (!empty($search_query)) {
            // Recherche des livres dont le titre correspond au terme recherché
            $stmt_livres = $pdo->prepare("SELECT id, titre, description, image, pdf 
                                          FROM `livres` 
                                          WHERE `auteur_id` = :auteur_id 
                                          AND `titre` LIKE :search_query");
            $search_param = '%' . $search_query . '%';
            $stmt_livres->bindParam(':auteur_id', $auteur_id, PDO::PARAM_INT);
            $stmt_livres->bindParam(':search_query', $search_param, PDO::PARAM_STR);
        } else {
            // Récupération de tous les livres de l'auteur
            $stmt_livres = $pdo->prepare("SELECT id, titre, description, image, pdf 
                                          FROM `livres` 
                                          WHERE `auteur_id` = :auteur_id");
            $stmt_livres->bindParam(':auteur_id', $auteur_id, PDO::PARAM_INT);
        }

        $stmt_livres->execute();
        $livres = $stmt_livres->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }
} else {
    // Redirection si l'ID de l'auteur n'est pas présent
    header('Location: bibliotheque.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Œuvres de <?php echo htmlspecialchars($auteur['nom'] ?? 'Auteur non trouvé'); ?></title>
    <link rel="stylesheet" href="Style.css">
    <link rel="shortcut icon" href="Images/daroul-alam.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <section id="header">
        <a href="index.php"><img src="Images/logo.png" class="logo" alt="Logo"></a>
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
        <h2>Œuvres de <?php echo htmlspecialchars($auteur['nom'] ?? 'Auteur non trouvé'); ?></h2>
        <form method="GET" action="">
            <input type="hidden" name="auteur_id" value="<?php echo htmlspecialchars($auteur_id); ?>">
            <input type="text" id="search" name="search" placeholder="Rechercher un livre..."
                value="<?php echo htmlspecialchars($search_query); ?>">
            <!-- <button class="btn" type="submit">Rechercher</button> -->
        </form>
    </section>

    <section class="livres">
        <?php if ($livres): ?>
            <?php foreach ($livres as $livre): ?>
                <div class="livre-card">
                    <img src="<?php echo $image_path . htmlspecialchars($livre['image']); ?>"
                        alt="<?php echo htmlspecialchars($livre['titre']); ?>">
                    <h3><?php echo !empty($livre['titre']) ? htmlspecialchars($livre['titre']) : 'Titre non disponible'; ?></h3>
                    <p><?php echo !empty($livre['description']) ? nl2br(htmlspecialchars($livre['description'])) : 'Description non disponible'; ?></p>
                    <?php if (!empty($livre['pdf'])): ?>
                        <a href="admin/livres/uploads/<?php echo htmlspecialchars($livre['pdf']); ?>" target="_blank">Visualiser le fichier</a>
                    <?php else: ?>
                        <span>Aucun PDF disponible</span>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucun livre trouvé pour votre recherche.</p>
        <?php endif; ?>

    </section>
     <!-- Bouton de retour après les cartes -->
     <div style="text-align: center; margin-top: 20px;">
        <a href="bibliotheque.php" class="back-button">
            <span class="arrow">&#8592;</span> Retour à la bibliothèque
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
            <input type="text" placeholder="Votre adresse mail">
            <button class="normal">Inscrivez-vous</button>
        </div>
    </section>

    <footer class="section-p1">
        <div class="col">
            <img class="logo" src="Images/logo.png" alt="Logo">
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
        <div class="col install">
            <h4>Installer l'application</h4>
            <p>Depuis l'App Store ou Google Play</p>
            <div class="row">
                <img src="IMG PAYER/app.jpg" alt="App Store">
                <img src="IMG PAYER/play.jpg" alt="Google Play">
            </div>
            <p>Modes de paiement sécurisées</p>
            <img src="IMG PAYER/pay.png" alt="" />
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

    <script defer src="Javascript/script.js"></script>
</body>

</html>