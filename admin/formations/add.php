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
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($imageFileType, $allowed_types)) {
            move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
        } else {
            echo "Seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
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
    </div>
</nav>

<header>
    <h1>Ajouter une Formation</h1>
</header>

<form action="add.php" method="POST" enctype="multipart/form-data">
    <label for="titre">Titre :</label>
    <input type="text" name="titre" id="titre" required><br><br>

    <label for="description">Description :</label>
    <textarea name="description" id="description" required></textarea><br><br>

    <label for="image">Image :</label>
    <input type="file" name="image" id="image" accept="image/*"><br><br>

    <input type="submit" value="Ajouter la Formation">
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
