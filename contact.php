<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Récupérer les données du formulaire
  $nom = $_POST['nom'];
  $email = $_POST['email'];
  $sujet = $_POST['sujet'];
  $message = $_POST['message'];

  // Insérer les données dans la base de données
  $stmt = $pdo->prepare("INSERT INTO messages (nom, email, sujet, message) 
                               VALUES (:nom, :email, :sujet, :message)");
  $stmt->bindParam(':nom', $nom);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':sujet', $sujet);
  $stmt->bindParam(':message', $message);

  if ($stmt->execute()) {
    header("Location: contact.php?success=Message envoye avec succès");
    exit();
  } else {
    echo "Erreur lors de  l'envoi.";
  }
}

// Récupérer les messages depuis la base de données
// $query = "SELECT * FROM messages ORDER BY date_envoi DESC"; // Assure-toi que ta table est correcte
// $stmt = $pdo->prepare($query);
// $stmt->execute();
// $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php

$message = ""; // Valeur par défaut
if (isset($_GET["success"])) {
  $message = $_GET["success"];
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact</title>
  <link rel="stylesheet" href="Style.css">
  <link
    rel="shortcut icon"
    href="Images/daroul-alam.png"
    type="image/x-icon" />
  <link
    rel="stylesheet"
    href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
  <!-- Inclure SweetAlert -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


</head>

<body>
  <section id="header">
    <a href="index.php"><img src="Images/logo.png" class="logo" alt="Logo" /></a>
    <div>
      <ul id="navbar">
        <li><a href="index.php">Accueil</a></li>
        <li><a href="formations.php">Formations</a></li>
        <li><a href="bibliotheque.php">Bibliothèque</a></li>
        <li><a href="propos.php">A Propos</a></li>
        <li><a href="blog.php">Blog</a></li>
        <li><a class="active" href="contact.php">Contact</a></li>
      </ul>
    </div>
    <div class="buttons">
      <a href="login/index.php"> <button class="sign-up">Se connecter</button></a>
    </div>
  </section>

  <section id="page-header" class="about-header">
    <h2>#Assistance/Support</h2>
    <p>Expertise et réactivité au service de vos besoins!</p>
  </section>

  <section id="contact-details" class="section-p1">
    <div class="details">

      <!-- Affichage des messages de succès ou d'erreur -->
      <?php if (!empty($message)) : ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
      <?php endif; ?>
      <?php if (isset($_GET['error'])) : ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
      <?php endif; ?>


      <span>NOS COORDONNÉES</span>
      <h2>Visitez l'une de nos agences ou contactez-nous dès maintenant.</h2>
      <h3>Siège social</h3>
      <div>
        <li>
          <i class="fal fa-map"></i>
          <p>Sid Al Khadir Imm 68 20700 CASABLANCA</p>
        </li>
        <li>
          <i class="far fa-envelope"></i>
          <p>support@daroul-alam.com</p>
        </li>
        <li>
          <i class="fas fa-phone-alt"></i>
          <p>+212 780003847/+221 77 793 89 69</p>
        </li>
        <li>
          <i class="far fa-clock"></i>
          <p>lundi - vendredi: 9h - 22h</p>
        </li>
      </div>
    </div>
    <div class="map">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d13298.414156786746!2d-7.691373849999999!3d33.5636757!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xda7d35167941945%3A0x45acb4dbd125b17b!2sSid%20Al%20Khadir%2C%20Casablanca!5e0!3m2!1sfr!2sma!4v1718935239391!5m2!1sfr!2sma"
        width="600"
        height="450"
        style="border: 0"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
  </section>

  <section id="form-details">

    <form action="contact.php" method="POST">
      <span>LAISSER UN MESSAGE</span>
      <h2>Nous aimons avoir de vos nouvelles</h2>
      <input type="text" name="nom" placeholder="Votre nom" required />
      <input type="email" name="email" placeholder="E-mail" required />
      <input type="text" name="sujet" placeholder="Sujet" required />
      <textarea name="message" cols="30" rows="10" placeholder="Votre message" required></textarea>
      <button class="normal" type="submit">Envoyer</button>
    </form>

    <div class="people">
      <div>
        <img class="rounded-circle" src="IMG PERS/ADJI 2.jpg" alt="" />
        <p>
          <span>**********</span> CEO, Directeur commercial<br />Numéro:
          +212 000000000 <br />
          E-mail: i.lo@daroul-alam.com
        </p>
      </div>
      <div>
        <img class="rounded-circle" src="Images/matar.JPG" alt="" />
        <p>
          <span>Matar Ndiaga Seck</span> Responsable marketing <br />Numéro:
          +212 000000000<br />
          E-mail: seckmatar9521@gmail.com
        </p>
      </div>
      <div>
        <img class="rounded-circle" src="Images/dame.jpg" alt="" />
        <p>
          <span>Dame Seck</span> Responsable technique <br />Numéro: +212
          000000000 <br />
          E-mail: dameseck207@gmail.com
        </p>
      </div>
    </div>
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
          rel="noopener">DAME SECK</a>
        <script>
          document.getElementById("year").textContent = new Date().getFullYear();
        </script>

    </div>
  </footer>
  <script src="Javascript/darul.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>