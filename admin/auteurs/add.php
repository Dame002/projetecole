<?php
// session_start();
// if (!isset($_SESSION['admin'])) {
//     header('Location: ../login.php');
//     exit();
// }
include '../../config.php';

// Vérification de l'accès admin
// if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
//     header("Location: ../login.php");
//     exit;
// }

// $message = "";
// Gestion de l'image

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = htmlspecialchars($_POST['nom']);
    $image = htmlspecialchars($_POST['image']);
    $description = htmlspecialchars($_POST['description']);
    $image = $_FILES['image']; // Récupérer l'image du formulaire

    // Gestion de l'image

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


    try {
        // Insertion dans la base de données
        $stmt = $pdo->prepare("INSERT INTO auteurs (nom, image, description) VALUES (:nom, :image, :description)");
        $stmt->bindValue(':nom', $nom, PDO::PARAM_STR);
        $stmt->bindParam(':image', $imageName); // Enregistrer le nom de l'image dans la base de données
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);



        if ($stmt->execute()) {
            echo "Auteur ajouté avec succès !";
            header("Location: list.php?success=Auteur ajoutè avec succès.");
        } else {
            echo "Erreur lors de l'ajout de l'auteur.";
        }
    } catch (PDOException $e) {
        $message = "❌ Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un auteur</title>
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
        <h1>Ajouter un auteur</h1>
    </header>

    <?php if (!empty($message)): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>

    <form action="add.php" method="POST" enctype="multipart/form-data">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom" required><br><br>
        <label for="image"> Image:</label>
        <input type="file" name="image" id="image" required><br><br>
        <label for="description">Description :</label>
        <textarea name="description" id="description" required></textarea><br><br>

        <input type="submit" value="Ajouter l'auteur">
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>