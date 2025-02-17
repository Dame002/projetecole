<?php
include '../../config.php';

$message = ""; // Message de succès ou erreur

// Vérification si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $auteur_id = $_POST['auteur_id'];
    $url = $_POST['url'];

    // Gérer l'image
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

    // Insérer le livre dans la base de données
    if (empty($message)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO livres (titre, description, auteur_id, image, url) VALUES (:titre, :description, :auteur_id,:image, :url)");

            // Lier les paramètres avec les valeurs
            $stmt->bindParam(':titre', $titre);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':image', $imageName); // Enregistrer le nom de l'image dans la base de données
            $stmt->bindParam(':url', $url);
            $stmt->bindParam(':auteur_id', $auteur_id);
            if ($stmt->execute()) {
                echo "livre ajouté avec succès !";
                header("Location: list.php?success=livres ajoute avec succès.");
            } else {
                echo "Erreur lors de l'ajout du livre.";
            }
        } catch (PDOException $e) {
            $message = "Erreur lors de l'ajout du livre: " . $e->getMessage();
        }
    }
}

// Récupérer la liste des auteurs pour le formulaire
$sql = "SELECT id, nom FROM auteurs";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$auteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Livre</title>
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
                        <a class="nav-link" href="list.php">livres</a>
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

    <div class="container mt-4">
        <header class="mb-3">
            <h1>Ajouter un Livre</h1>
        </header>

        <!-- Affichage des messages de succès ou d'erreur -->
        <?php if (!empty($message)) : ?>
            <div class="alert <?php echo (strpos($message, 'Erreur') === false) ? 'alert-success' : 'alert-danger'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form action="add.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="titre" class="form-label">Titre</label>
                <input type="text" class="form-control" id="titre" name="titre" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label for="auteur_id" class="form-label">Auteur</label>
                <select class="form-select" id="auteur_id" name="auteur_id" required>
                    <option value="">Choisir un auteur</option>
                    <?php foreach ($auteurs as $auteur): ?>
                        <option value="<?php echo $auteur['id']; ?>"><?php echo htmlspecialchars($auteur['nom']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="url" class="form-label">URL</label>
                <input type="text" class="form-control" id="url" name="url" required>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" class="form-control" id="image" name="image">
            </div>

            <button type="submit" class="btn btn-primary">Ajouter le livre</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>