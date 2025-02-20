<?php
// session_start();
// if (!isset($_SESSION['admin'])) {
//     header('Location: ../login.php');
//     exit();
// }
include '../../config.php';

// Vérification de l'ID
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: list.php?error=ID invalide");
    exit;
}

$id = (int) $_GET['id']; // Assurer que c'est un entier

// Récupération des données de l'auteur
try {
    $stmt = $pdo->prepare("SELECT * FROM auteurs WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $formation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$formation) {
        header("Location: list.php?error=Auteur introuvable");
        exit;
    }
} catch (PDOException $e) {
    header("Location: list.php?error=Erreur SQL: " . urlencode($e->getMessage()));
    exit;
}

// Traitement de la mise à jour
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = trim($_POST['nom']);
    $image = $_FILES['image'];  // Récupération du fichier téléchargé
    $description = trim($_POST['description']);

    // Vérifier que tous les champs sont remplis
    if (!empty($nom) && !empty($description)) {
        $imageName = $formation['image']; // Garde l'image existante par défaut

        // Si une nouvelle image est téléchargée
        if ($image['error'] == 0) {
            // Vérification du format de l'image (par exemple, jpg, png)
            $validFormats = ['image/jpeg', 'image/png', 'image/gif'];
            if (in_array($image['type'], $validFormats)) {
                // Définir un nom unique pour l'image
                $imageName = uniqid() . "_" . basename($image['name']);
                $uploadDir = 'uploads/'; // Le dossier où l'image sera enregistrée
                $uploadPath = $uploadDir . $imageName;

                // Déplacer le fichier téléchargé vers le dossier de destination
                if (move_uploaded_file($image['tmp_name'], $uploadPath)) {
                    // Supprimer l'ancienne image si elle existe dans le dossier
                    if ($formation['image'] && file_exists($uploadDir . $formation['image'])) {
                        unlink($uploadDir . $formation['image']);
                    }
                } else {
                    $error = "Erreur lors du téléchargement de l'image.";
                }
            } else {
                $error = "Format d'image invalide. Seuls les fichiers JPG, PNG, GIF sont autorisés.";
            }
        }

        if (!isset($error)) {
            try {
                // Mettre à jour les informations de l'auteur dans la base de données
                $stmt = $pdo->prepare("UPDATE auteurs SET nom = :nom, image = :image, description = :description WHERE id = :id");
                $stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
                $stmt->bindValue(':image', $imageName, PDO::PARAM_STR);
                $stmt->bindValue(':description', $description, PDO::PARAM_STR);
                $stmt->bindValue(':id', $id, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    header("Location: list.php?success=Auteur modifié avec succès");
                    exit;
                } else {
                    $error = "Erreur lors de la modification.";
                }
            } catch (PDOException $e) {
                $error = "Erreur SQL: " . $e->getMessage();
            }
        }
    } else {
        $error = "Tous les champs sont obligatoires.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'auteur</title>
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
            <a class="nav-link active" aria-current="page" href="../cours/list.php">Cours</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../formations/list.php">Formations</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../livres/list.php">livres</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="list.php">Auteurs</a>
          </li>
          <li class="nav-item">
                        <a class="nav-link" href="../Messages/liste_contact.php">Messages</a>
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
        <h1>Modifier l'auteur</h1>
    </header>

    <?php if (isset($error)) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form action="edit.php?id=<?php echo $id; ?>" method="POST" enctype="multipart/form-data">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom" value="<?php echo htmlspecialchars($formation['nom']); ?>" required><br><br>

        <label for="image">Image :</label>
        <?php if ($formation['image']) : ?>
            <img src="../../uploads/<?php echo $formation['image']; ?>" style="width: 100px; height: auto;" alt="Image actuelle">
        <?php endif; ?>
        <input type="file" name="image" id="image"><br><br> <!-- Champ facultatif pour télécharger une nouvelle image -->

        <label for="description">Description :</label>
        <textarea name="description" id="description" required><?php echo htmlspecialchars($formation['description']); ?></textarea><br><br>

        <input type="submit" value="Modifier l'auteur" class="btn btn-primary">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
