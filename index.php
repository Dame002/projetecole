<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>DAARUL ALAM</title>
    <link rel="stylesheet" href="Style.css" />
    <link
      rel="shortcut icon"
      href="Images/daroul-alam.png"
      type="image/x-icon"
    />
    <link
      rel="stylesheet"
      href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
    />
  </head>
  <body>
    <section id="header">
      <a href="index.php"
        ><img src="Images/logo.png" class="logo" alt="Logo"
      /></a>
      <div>
        <ul id="navbar">
          <li><a class="active" href="index.php">Accueil</a></li>
          <li><a href="bibliotheque.php">Bibliothèque</a></li>
          <!-- <li><a href="cours.php">Cours</a></li> -->
          <li><a href="formations.php">Formations</a></li>
          <li><a href="propos.php">A Propos</a></li>
          <li><a href="contact.php">Contact</a></li>
          <li><a href="blog.php">Blog</a></li>
        </ul>
      </div>
      <div class="buttons">
       <a href="login/index.php"> <button class="sign-up">Se connecter</button> </a>
        <!-- <a href="index.html"><button class="log-in">S'inscrire</button>  </a> -->
      </div>
    </section>

    <section id="hero">
      <div class="background-blur"></div>
      <h1>Plateforme dédiée à l'apprentissage de la religion islamique !</h1>
      <button><a href="#">Inscrivez-vous !</a></button>
    </section>
    <br /><br />
    <section class="grid-section">
      <div class="grid-container">
        <div class="image grid-item-1">
          <img src="IMG GRID/quran3.jpg" alt="Main Image" />
          <div class="overlay">
            <h3>Apprendre le Coran</h3>
            <p>
              Dans cette rubrique, découvrez les bases de la lecture et de la
              compréhension du Coran. <br />
              Un guide pour maîtriser la récitation et approfondir votre
              connexion spirituelle.
            </p>
            <button class="btn">
              <a href="#">Voir formations</a>
            </button>
          </div>
        </div>
        <div class="image grid-item-2">
          <img src="IMG GRID/prières.jpg" alt="Related Image 1" />
          <div class="overlay">
            <h3>Apprendre les bases de la prière</h3>
            <p>
              Dans cette rubrique, vous apprendrez les gestes et paroles
              essentiels pour accomplir la prière correctement. Un guide pour
              débutants et révisions.
            </p>
            <button class="btn">
              <a chref="#">Voir Formations</a>
            </button>
          </div>
        </div>
        <div class="image grid-item-3">
          <img src="IMG GRID/hadiths2.jpg" alt="Related Image 2" />
          <div class="overlay">
            <h3>Apprendre les hadiths</h3>
            <p>
              Dans cette rubrique, explorez les enseignements et récits des
              actions et paroles du Prophète (paix et bénédictions sur lui), et
              comprenez leur impact sur la vie spirituelle et quotidienne.
            </p>
            <button class="btn">
              <a href="#">Voir Formations</a>
            </button>
          </div>
        </div>
      </div>
    </section>
    <div class="image grid-item-horizontal">
      <img src="IMG GRID/horizontal2.jpg" alt="Horizontal Image">
      <div class="overlay">
        <h3>Explorez davantage</h3>
        <p>Découvrez d'autres ressources pour approfondir vos connaissances religieuses.</p>
        <button class="btn"><a href="#">Dècouvrir</a></button>
      </div>
    </div>

    <br /><br />

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

    <script src="Javascript/darul.js"></script>
  </body>
</html>
