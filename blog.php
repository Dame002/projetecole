<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blog</title>
  <link
    rel="shortcut icon"
    href="Images/daroul-alam.png"
    type="image/x-icon" />
  <link
    rel="stylesheet"
    href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <section id="header">
    <a href="index.php"><img src="Images/logo.png" class="logo" alt="Logo" /></a>
    <div>
      <ul id="navbar">
      <li><a  href="index.php">Accueil</a></li>
          <li><a href="bibliotheque.php">Bibliothèque</a></li>
          <!-- <li><a href="cours.php">Cours</a></li> -->
          <li><a href="formations.php">Formations</a></li>
          <li><a href="propos.php">A Propos</a></li>
          <li><a href="contact.php">Contact</a></li>
          <li><a class="active" href="blog.php">Blog</a></li>
      </ul>
    </div>
    <div class="buttons">
      <a href="login/index.php"><button class="sign-up">Se connecter</button></a>
      <!-- <a href=""><button class="log-in">S'inscrire</button> </a> -->
    </div>
  </section>
  <section id="page-header" class="blog-header">
    <h2>#SavoirPlus</h2>
    <p>Éveille ta foi, découvre la sagesse!</p>
  </section>

  <section id="blog">
    <div class="blog-box">
      <div class="blog-img">
        <img src="IMG BLOG/b1.jpg" alt="" />
      </div>
      <div class="blog-details">
        <h4>Histoires : Les figures emblématiques de l'islam</h4>
        <p>
          Explorez les vies de grandes figures islamiques qui ont inspiré des générations. Leur dévouement et leur foi sont des modèles à suivre....
        </p>
        <a href="#">CONTINUER LA LECTURE</a>
      </div>
      <h1>15/06</h1>
    </div>
    <div class="blog-box">
      <div class="blog-img">
        <img src="IMG BLOG/b6.jpg" alt="" />
      </div>
      <div class="blog-details">
        <h4>Maximisez votre temps : Organiser prières et études islamiques</h4>
        <p>
          Apprenez à gérer votre temps pour combiner efficacement prières et études. Découvrez des méthodes simples pour mieux organiser votre quotidien.

          ...
        </p>
        <a href="#">CONTINUER LA LECTURE</a>
      </div>
      <h1>17/06</h1>
    </div>
    <div class="blog-box">
      <div class="blog-img">
        <img src="IMG BLOG/b3.jpg" alt="" />
      </div>
      <div class="blog-details">
        <h4>Ramadan et Aïd : Comprendre leur vraie signification</h4>
        <p>
          Le Ramadan et l’Aïd sont des moments de purification spirituelle. Apprenez les leçons profondes que ces périodes offrent....
        </p>
        <a href="#">CONTINUER LA LECTURE</a>
      </div>
      <h1>19/06</h1>
    </div>
    <div class="blog-box">
      <div class="blog-img">
        <img src="IMG BLOG/b2.jpg" alt="" />
      </div>
      <div class="blog-details">
        <h4>Réponses aux questions courantes : Démystification de la foi</h4>
        <p>
          Vous vous posez des questions sur la foi islamique ? Cet article répond aux interrogations fréquentes des débutants....
        </p>
        <a href="#">CONTINUER LA LECTURE</a>
      </div>
      <h1>20/06</h1>
    </div>
    <div class="blog-box">
      <div class="blog-img">
        <img src="IMG BLOG/b5.jpg" alt="" />
      </div>
      <div class="blog-details">
        <h4>Être un jeune musulman aujourd’hui : Trouver l’équilibre</h4>
        <p>
          Naviguer entre la foi et la vie moderne peut être difficile. Découvrez des conseils pour concilier les deux sans compromettre vos valeurs....
        </p>
        <a href="#">CONTINUER LA LECTURE</a>
      </div>
      <h1>22/06</h1>
    </div>
  </section>

  <section id="pagination" class="section-p1">
    <a href="#">1</a>
    <a href="#">2</a>
    <a href="#"><i class="fal fa-long-arrow-alt-right"></i></a>
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
          rel="noopener">DAME SECK</a>
        <script>
          document.getElementById("year").textContent = new Date().getFullYear();
        </script>

    </div>
  </footer>

  <script src="Javascript/darul.js"></script>
</body>
</body>

</html>