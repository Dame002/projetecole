<?php
include '../../config.php';

// Vérification d'une URL valide (tous types de liens)
function isValidUrl($url) {
    return filter_var($url, FILTER_VALIDATE_URL);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $url = $_POST['url'];
    $formation_id = $_POST['formation_id'];
    $imageName = null;

    // Gestion de l'image
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image'];
        $imageName = uniqid() . "_" . basename($image['name']);
        $uploadDir = "uploads/";
        $uploadPath = $uploadDir . $imageName;
        $validTypes = ['image/jpeg', 'image/png', 'image/gif'];

        if (in_array($image['type'], $validTypes) && $image['size'] <= 2 * 1024 * 1024) {
            move_uploaded_file($image['tmp_name'], $uploadPath);
        } else {
            echo "Image invalide ou trop volumineuse.";
            exit();
        }
    }

    // Vérification des champs
    if (empty($titre) || empty($description) || empty($url) || empty($formation_id)) {
        echo "Tous les champs sont obligatoires !";
    } elseif (!isValidUrl($url)) {
        echo "L'URL fournie n'est pas valide.";
    } else {
        // Insertion dans la base de données
        $stmt = $pdo->prepare("INSERT INTO cours (titre, description, image, url, formation_id) 
                               VALUES (:titre, :description, :image, :url, :formation_id)");
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image', $imageName);
        $stmt->bindParam(':url', $url);
        $stmt->bindParam(':formation_id', $formation_id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            header("Location: list.php?success=Cours ajouté avec succès. <br>Accédez au cours ici : <a href='$url'>$url</a>");
            exit();
        } else {
            echo "Erreur lors de l'ajout du cours.";
        }
    }
}

// Récupérer les formations pour l'affichage dans le formulaire
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">Daarul Alam</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link active" href="list.php">Cours</a></li>
                    <li class="nav-item"><a class="nav-link" href="../formations/list.php">Formations</a></li>
                    <li class="nav-item"><a class="nav-link" href="../livres/list.php">Livres</a></li>
                    <li class="nav-item"><a class="nav-link" href="../auteurs/list.php">Auteurs</a></li>
                    <li class="nav-item"><a class="nav-link" href="../create_admin.php">Administrateurs</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header>
        <h1>Ajouter un Cours</h1>
    </header>

    <form action="add.php" method="POST" enctype="multipart/form-data" id="courseForm">
        <label for="formation_id">Formation :</label>
        <select name="formation_id" id="formation_id" required>
            <?php foreach ($formations as $formation): ?>
                <option value="<?= $formation['id']; ?>"><?= htmlspecialchars($formation['titre']); ?></option>
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

        <input type="submit" value="Ajouter le Cours" class="btn btn-primary">
    </form>

    <script>
        document.getElementById("courseForm").addEventListener("submit", function(event) {
            var url = document.getElementById("url").value;
            var urlRegex = /^(https?:\/\/)?([\w-]+(\.[\w-]+)+)(\/[\w-./?%&=]*)?$/i;

            if (!urlRegex.test(url)) {
                alert("Veuillez entrer une URL valide !");
                event.preventDefault();
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
