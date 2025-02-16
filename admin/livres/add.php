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
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_name = basename($_FILES['image']['name']);
        $image_tmp = $_FILES['image']['tmp_name'];
        $upload_dir = '../../uploads/';
        $image_path = $upload_dir . $image_name;

        // Vérifier si le dossier d'uploads existe, sinon le créer
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Redimensionner l'image
        $image_info = getimagesize($image_tmp);
        $image_width = $image_info[0]; // Largeur de l'image
        $image_height = $image_info[1]; // Hauteur de l'image

        // Taille souhaitée (largeur)
        $new_width = 150;
        $new_height = ($image_height / $image_width) * $new_width; // Garder les proportions

        // Créer une image à partir du fichier original
        switch ($image_info['mime']) {
            case 'image/jpeg':
                $src_image = imagecreatefromjpeg($image_tmp);
                break;
            case 'image/png':
                $src_image = imagecreatefrompng($image_tmp);
                break;
            case 'image/gif':
                $src_image = imagecreatefromgif($image_tmp);
                break;
            default:
                $message = "Format d'image non pris en charge.";
                break;
        }

        if (!isset($src_image)) {
            // Si l'image n'a pas été chargée correctement
            $message = "Erreur lors du traitement de l'image.";
        } else {
            // Créer une nouvelle image vide pour la redimensionner
            $dst_image = imagecreatetruecolor($new_width, $new_height);
            
            // Redimensionner l'image sans déformation
            imagecopyresampled($dst_image, $src_image, 0, 0, 0, 0, $new_width, $new_height, $image_width, $image_height);

            // Enregistrer l'image redimensionnée dans le dossier 'uploads'
            switch ($image_info['mime']) {
                case 'image/jpeg':
                    imagejpeg($dst_image, $image_path, 90); // qualité 90 pour jpeg
                    break;
                case 'image/png':
                    imagepng($dst_image, $image_path);
                    break;
                case 'image/gif':
                    imagegif($dst_image, $image_path);
                    break;
            }

            // Libérer la mémoire utilisée par l'image source et destination
            imagedestroy($src_image);
            imagedestroy($dst_image);

            $image = $image_name; // Le nom de l'image à enregistrer dans la base
        }
    } else {
        // Si aucune image n'a été téléchargée, on peut décider de ne pas mettre d'image
        $image = null;
    }

    // Insérer le livre dans la base de données
    if (empty($message)) {
        try {
            $sql = "INSERT INTO livres (titre, description, auteur_id, image, url) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$titre, $description, $auteur_id, $image, $url]);
            $message = "Livre ajouté avec succès.";
            header("Location: list.php?success=" . urlencode($message)); // Rediriger avec message de succès
            exit();
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
