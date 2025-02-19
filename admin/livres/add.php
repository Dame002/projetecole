<?php
include '../../config.php';

$message = ""; // Message de succès ou d'erreur

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $titre       = $_POST['titre'];
    $description = $_POST['description'];
    $auteur_id   = $_POST['auteur_id'];
    $url         = $_POST['url'];

    // Initialisation des variables pour stocker les noms de fichiers
    $imageName = "";
    $pdfName   = "";

    // === Gérer l'upload de l'image ===
    $image = $_FILES['image'];
    if ($image['error'] === 0) {
        // Générer un nom unique pour éviter les conflits
        $imageName = uniqid() . "_" . basename($image['name']);
        $uploadDir = "uploads/";
        $uploadPath = $uploadDir . $imageName;

        // Vérifier le type MIME de l'image
        $validTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($image['type'], $validTypes)) {
            // Taille max autorisée : 2 Mo
            if ($image['size'] <= 2 * 1024 * 1024) {
                if (!move_uploaded_file($image['tmp_name'], $uploadPath)) {
                    $message .= "Erreur lors du téléchargement de l'image. ";
                }
            } else {
                $message .= "L'image est trop grande. La taille maximale autorisée est de 2 Mo. ";
            }
        } else {
            $message .= "Seules les images JPG, PNG et GIF sont autorisées. ";
        }
    } else {
        // Optionnel : si aucune image n'est téléchargée, on peut aussi le gérer ici
        // $message .= "Aucune image téléchargée. ";
    }

    // === Gérer l'upload du PDF ===
    $pdf = $_FILES['pdf'];
    if ($pdf['error'] === 0) {
        // Générer un nom unique pour le PDF
        $pdfName = uniqid() . "_" . basename($pdf['name']);
        $uploadDir = "uploads/";
        $uploadPathPdf = $uploadDir . $pdfName;

        // Vérifier le type MIME (seulement PDF)
        $validTypesPdf = ['application/pdf'];
        if (in_array($pdf['type'], $validTypesPdf)) {
            // Taille max autorisée : 50 Mo (augmentée pour des fichiers volumineux)
            if ($pdf['size'] <= 50 * 1024 * 1024) {
                if (!move_uploaded_file($pdf['tmp_name'], $uploadPathPdf)) {
                    $message .= "Erreur lors du téléchargement du PDF. ";
                }
            } else {
                $message .= "Le fichier PDF est trop grand. La taille maximale autorisée est de 50 Mo. ";
            }
        } else {
            $message .= "Seuls les fichiers PDF sont autorisés. ";
        }
    } else {
        // Optionnel : message si aucun PDF n'est uploadé
        // $message .= "Aucun fichier PDF téléchargé. ";
    }

    // === Insertion dans la base de données ===
    if (empty($message)) {
        try {
            // Assure-toi d'avoir une colonne 'pdf' dans ta table 'livres'
            $stmt = $pdo->prepare("INSERT INTO livres (titre, description, auteur_id, image, url, pdf) VALUES (:titre, :description, :auteur_id, :image, :url, :pdf)");

            $stmt->bindParam(':titre', $titre);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':auteur_id', $auteur_id);
            $stmt->bindParam(':image', $imageName);
            $stmt->bindParam(':url', $url);
            $stmt->bindParam(':pdf', $pdfName);

            if ($stmt->execute()) {
                header("Location: list.php?success=livre ajouté avec succès.");
                exit();
            } else {
                $message .= "Erreur lors de l'ajout du livre dans la base de données.";
            }
        } catch (PDOException $e) {
            $message .= "Erreur lors de l'ajout du livre: " . $e->getMessage();
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="../index.php">Daarul Alam</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="../cours/list.php">Cours</a></li>
                    <li class="nav-item"><a class="nav-link" href="../formations/list.php">Formations</a></li>
                    <li class="nav-item"><a class="nav-link active" href="list.php">Livres</a></li>
                    <li class="nav-item"><a class="nav-link" href="../auteurs/list.php">Auteurs</a></li>
                    <li class="nav-item"><a class="nav-link" href="../create_admin.php">Administrateurs</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Contenu principal -->
    <div class="container mt-4">
        <header class="mb-3">
            <h1>Ajouter un Livre</h1>
        </header>

        <!-- Affichage des messages -->
        <?php if (!empty($message)) : ?>
            <div class="alert <?php echo (strpos($message, 'Erreur') === false) ? 'alert-success' : 'alert-danger'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Formulaire d'ajout -->
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
            <div class="mb-3">
                <label for="pdf" class="form-label">PDF</label>
                <input type="file" class="form-control" id="pdf" name="pdf">
            </div>
            <button type="submit" class="btn btn-primary">Ajouter le livre </button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
