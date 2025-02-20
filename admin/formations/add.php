<?php
include '../../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = htmlspecialchars($_POST['titre']);
    $description = htmlspecialchars($_POST['description']);
    $image = "";

    // Gestion de l'upload d'image
    if (!empty($_FILES['image']['name'])) {
        $target_dir = "../../uploads/";
        $image = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Vérification du type de fichier
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (in_array($imageFileType, $allowed_types)) {
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        } else {
            echo "Seuls les fichiers JPG, JPEG, PNG, GIF et webp sont autorisés.";
            exit();
        }
    }

    try {
        $stmt = $pdo->prepare("INSERT INTO formations (titre, description, image) VALUES (:titre, :description, :image)");
        $stmt->bindValue(':titre', $titre, PDO::PARAM_STR);
        $stmt->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt->bindValue(':image', $image, PDO::PARAM_STR);

        if ($stmt->execute()) {
            header("Location: list.php?success=Formation ajoutée avec succès.");
            exit();
        } else {
            echo "Erreur lors de l'ajout de la formation.";
        }
    } catch (PDOException $e) {
        echo "❌ Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Formation</title>
    <link rel="stylesheet" href="../../assets/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="../index.php">Daarul Alam</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link active" href="list.php">Cours</a></li>
                <li class="nav-item"><a class="nav-link active" href="../formations/list.php">Formations</a></li>
                <li class="nav-item"><a class="nav-link" href="../livres/list.php">Livres</a></li>
                <li class="nav-item"><a class="nav-link" href="../auteurs/list.php">Auteurs</a></li>
                <li class="nav-item"><a  class="nav-link" href="../Messages/liste_contact.php">Messages</a></li>
                <li class="nav-item"><a class="nav-link" href="../create_admin.php">Administrateurs</a></li>
            </ul>
        </div>
    </div>
</nav>

<header>
    <h1>Ajouter une Formation</h1>
</header>

<form action="add.php" method="POST" enctype="multipart/form-data" id="formationForm">
    <div class="mb-3">
        <label for="titre" class="form-label">Titre :</label>
        <input type="text" name="titre" id="titre" class="form-control" required>
    </div>

    <div class="mb-3">
        <label for="description" class="form-label">Description :</label>
        <textarea name="description" id="description" class="form-control" required></textarea>
    </div>

    <div class="mb-3">
        <label for="image" class="form-label">Image :</label>
        <input type="file" name="image" id="image" class="form-control" accept="image/*">
    </div>

    <div class="mb-3">
        <input type="submit" value="Ajouter la Formation" class="btn btn-primary">
    </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
