<?php
// session_start();
// if (!isset($_SESSION['admin'])) {
//     header('Location: ../login.php');
//     exit();
// }
include '../../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $url = $_POST['url'];
    $formation_id = $_POST['formation_id'];

    // Gestion de l'image
    $image = $_FILES['image']; // Récupérer l'image du formulaire

    // Vérifier que l'image a bien été envoyée
    if ($image['error'] == 0) {
        // Définir un nom unique pour l'image pour éviter les conflits
        $imageName = uniqid() . "_" . basename($image['name']);

        // Spécifier le dossier de destination pour l'image
        $uploadDir = "uploads/";
        $uploadPath = $uploadDir . $imageName;

        // Vérifier le type de fichier (par exemple, acceptons seulement des images PNG, JPG, JPEG)
        $validTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($image['type'], $validTypes)) {
            // Vérifier la taille de l'image (exemple : max 2 Mo)
            if ($image['size'] <= 2 * 1024 * 1024) {
                // Déplacer l'image dans le dossier "uploads"
                if (move_uploaded_file($image['tmp_name'], $uploadPath)) {
                    echo "Image uploadée avec succès !";
                } else {
                    echo "Erreur lors du téléchargement de l'image.";
                }
            } else {
                echo "L'image est trop grande. La taille maximale autorisée est de 2 Mo.";
            }
        } else {
            echo "Seules les images JPG, PNG et GIF sont autorisées.";
        }
    } else {
        echo "Aucune image téléchargée.";
    }

    // Vérification des autres données
    if (empty($titre) || empty($description) || empty($url) || empty($formation_id)) {
        echo "Tous les champs sont obligatoires !";
    } else {
        // Insertion dans la base de données
        $stmt = $pdo->prepare("INSERT INTO cours (titre, description, image, url, formation_id) VALUES (:titre, :description, :image, :url, :formation_id)");

        // Lier les paramètres avec les valeurs
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image', $imageName); // Enregistrer le nom de l'image dans la base de données
        $stmt->bindParam(':url', $url);
        $stmt->bindParam(':formation_id', $formation_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo "Cours ajouté avec succès !";
            header("Location: list.php?success=Cours ajoute avec succès.");
        } else {
            echo "Erreur lors de l'ajout du cours.";
        }
    }
}

// Récupérer les formations pour l'option du formulaire
$stmt = $pdo->query("SELECT * FROM formations");
$formations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Cours</title>
    <link rel="stylesheet" href="../../assets/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand" href="../index.php">Daarul Alam</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="list.php">Cours</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../formations/list.php">Formations</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../livres/list.php">livres</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../auteurs/list.php">Auteurs</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../create_admin.php">Administrateurs</a>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link disabled" aria-disabled="true">Disabled</a>
          </li> -->
        </ul>
      </div>
    </div>
  </nav>

    <header>
        <h1>Ajouter un Cours</h1>
    </header>

    <form action="add.php" method="POST" enctype="multipart/form-data">

        <label for="formation_id">Formation :</label>
        <select name="formation_id" id="formation_id" required>
            <?php foreach ($formations as $formation): ?>
                <option value="<?php echo $formation['id']; ?>"><?php echo htmlspecialchars($formation['titre']); ?></option>
            <?php endforeach; ?>
        </select>
        <label for="titre">Titre :</label>
        <input type="text" name="titre" id="titre" required><br><br>

        <label for="description">Description :</label>
        <textarea name="description" id="description" required></textarea><br><br>

        <label for="image">Image :</label>
        <input type="file" name="image" id="image"><br><br>

        <label for="url">URL :</label>
        <input type="text" name="url" id="url" required><br><br>

        <br><br>

        <input type="submit" value="Ajouter le Cours">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>