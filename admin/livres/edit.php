<?php
// session_start();
// if (!isset($_SESSION['admin'])) {
//     header('Location: ../login.php');
//     exit();
// }
include '../../config.php';

// Récupérer l'ID du livre
$id = $_GET['id'];

// Récupérer le livre à modifier
$stmt = $pdo->prepare("SELECT * FROM livres WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$livre = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$livre) {
    die("Livre introuvable.");
}

// Récupérer la liste des auteurs
$stmt = $pdo->prepare("SELECT * FROM auteurs");
$stmt->execute();
$auteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $url = $_POST['url'];
    $auteur_id = $_POST['auteur_id'];  // ID de l'auteur sélectionné

    // Validation de l'URL
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        die("L'URL fournie n'est pas valide.");
    }

    // Récupérer l'ancienne image et l'ancien PDF
    $imagePath = $livre['image'] ?? '';
    $pdfPath   = $livre['pdf'] ?? '';

    // === Gestion de l'image ===
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image'];
        $uploadDir = "uploads/";
        // Vérifier le type et la taille de l'image
        $validTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($image['type'], $validTypes) && $image['size'] <= 2 * 1024 * 1024) {
            $imageName = uniqid() . "_" . basename($image['name']);
            $uploadPath = $uploadDir . $imageName;

            // Supprimer l'ancienne image si elle existe
            if (!empty($imagePath) && file_exists($uploadDir . $imagePath)) {
                unlink($uploadDir . $imagePath);
            }

            // Déplacer la nouvelle image et mettre à jour le chemin
            if (move_uploaded_file($image['tmp_name'], $uploadPath)) {
                $imagePath = $imageName;
            } else {
                die("Erreur lors du téléchargement de l'image.");
            }
        } else {
            die("Image invalide. Vérifiez le format et la taille.");
        }
    }

    // === Gestion du PDF ===
    if (isset($_FILES['pdf']) && $_FILES['pdf']['error'] == 0) {
        $pdf = $_FILES['pdf'];
        $uploadDir = "uploads/";
        // On accepte uniquement le format PDF et limite à 10 Mo
        if (in_array($pdf['type'], ['application/pdf']) && $pdf['size'] <= 10 * 1024 * 1024) {
            $pdfName = uniqid() . "_" . basename($pdf['name']);
            $uploadPathPdf = $uploadDir . $pdfName;

            // Supprimer l'ancien PDF s'il existe
            if (!empty($pdfPath) && file_exists($uploadDir . $pdfPath)) {
                unlink($uploadDir . $pdfPath);
            }

            if (move_uploaded_file($pdf['tmp_name'], $uploadPathPdf)) {
                $pdfPath = $pdfName;
            } else {
                die("Erreur lors du téléchargement du PDF.");
            }
        } else {
            die("PDF invalide. Vérifiez le format et la taille.");
        }
    }

    // Mise à jour du livre dans la base de données
    $stmt = $pdo->prepare("UPDATE livres 
                           SET titre = :titre, description = :description, image = :image, url = :url, auteur_id = :auteur_id, pdf = :pdf 
                           WHERE id = :id");
    $stmt->bindParam(':titre', $titre, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':image', $imagePath, PDO::PARAM_STR);
    $stmt->bindParam(':url', $url, PDO::PARAM_STR);
    $stmt->bindParam(':auteur_id', $auteur_id, PDO::PARAM_INT);
    $stmt->bindParam(':pdf', $pdfPath, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: list.php?success=Modification réussie");
        exit();
    } else {
        echo "Erreur lors de la modification du livre.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Livre</title>
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
            <a class="nav-link" href="list.php">Livres</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../auteurs/list.php">Auteurs</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../create_admin.php">Administrateurs</a>
          </li>
        </ul>
      </div>
    </div>
</nav>

<header class="container mt-4">
    <h1>Modifier le Livre</h1>
</header>

<div class="container mt-4">
    <form action="edit.php?id=<?php echo $livre['id']; ?>" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $livre['id']; ?>">

        <!-- Auteur -->
        <div class="mb-3">
            <label for="auteur_id" class="form-label">Auteur :</label>
            <select name="auteur_id" id="auteur_id" class="form-select" required>
                <?php foreach ($auteurs as $auteur): ?>
                    <option value="<?php echo $auteur['id']; ?>" <?php echo ($auteur['id'] == $livre['auteur_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($auteur['nom']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Titre -->
        <div class="mb-3">
            <label for="titre" class="form-label">Titre :</label>
            <input type="text" name="titre" id="titre" class="form-control" value="<?php echo htmlspecialchars($livre['titre']); ?>" required>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Description :</label>
            <textarea name="description" id="description" class="form-control" required><?php echo htmlspecialchars($livre['description']); ?></textarea>
        </div>

        <!-- Image -->
        <div class="mb-3">
            <label for="image" class="form-label">Image :</label>
            <input type="file" name="image" id="image" class="form-control">
        </div>

        <!-- Affichage de l'image actuelle si elle existe -->
        <div class="mb-3">
            <label class="form-label">Image actuelle :</label><br>
            <img src="uploads/<?php echo (!empty($livre['image']) && file_exists("uploads/" . $livre['image'])) ? htmlspecialchars($livre['image']) : 'default-image.jpg'; ?>" width="150" alt="Image du livre">
        </div>

        <!-- PDF -->
        <div class="mb-3">
            <label for="pdf" class="form-label">PDF :</label>
            <input type="file" name="pdf" id="pdf" class="form-control">
        </div>

        <!-- Affichage du PDF actuel s'il existe -->
        <?php if (!empty($livre['pdf']) && file_exists("uploads/" . $livre['pdf'])): ?>
            <div class="mb-3">
                <label class="form-label">PDF actuel :</label><br>
                <a href="uploads/<?php echo htmlspecialchars($livre['pdf']); ?>" target="_blank" class="btn btn-info btn-sm">Voir le PDF</a>
            </div>
        <?php endif; ?>

        <!-- URL -->
        <div class="mb-3">
            <label for="url" class="form-label">URL :</label>
            <input type="text" name="url" id="url" class="form-control" value="<?php echo htmlspecialchars($livre['url']); ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Modifier le Livre</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" 
        crossorigin="anonymous"></script>
</body>
</html>
