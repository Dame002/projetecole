<?php
include '../../config.php';

// Récupérer l'ID du cours
$id = $_GET['id'];

// Récupérer le cours à modifier
$stmt = $pdo->prepare("SELECT * FROM cours WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$cour = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt = $pdo->prepare("SELECT id, titre FROM formations");
$stmt->execute();
$formations = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$cour) {
    die("Cours introuvable.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $url = $_POST['url'];
    $formation_id = $_POST['formation_id'];

    // Récupérer l'ancienne image
    $imagePath = $cour['image'] ?? ''; 

    // Vérifier si une nouvelle image a été envoyée
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = $_FILES['image'];

        // Définir le dossier d'upload
        $uploadDir = "uploads/";
        
        // Vérifier le type et la taille de l'image
        $validTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($image['type'], $validTypes) && $image['size'] <= 2 * 1024 * 1024) {
            // Générer un nom unique
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

    // Mise à jour du cours
    $stmt = $pdo->prepare("UPDATE cours SET titre = :titre, description = :description, image = :image, url = :url, formation_id = :formation_id WHERE id = :id");
    $stmt->bindParam(':titre', $titre, PDO::PARAM_STR);
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->bindParam(':image', $imagePath, PDO::PARAM_STR);
    $stmt->bindParam(':url', $url, PDO::PARAM_STR);
    $stmt->bindParam(':formation_id', $formation_id, PDO::PARAM_INT);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        echo "Cours modifié avec succès !";
        header("Location: list.php?success=Modification réussie");
        exit();
    } else {
        echo "Erreur lors de la modification du cours.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Cours</title>
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
          <li class="nav-item"><a class="nav-link" href="../Messages/liste_contact.php">Messages</a></li>
          <li class="nav-item"><a class="nav-link" href="../create_admin.php">Administrateurs</a></li>
        </ul>
      </div>
    </div>
</nav>

<header>
    <h1>Modifier le Cours</h1>
</header>

<form action="edit.php?id=<?php echo $cour['id']; ?>" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?php echo $cour['id']; ?>">

    <label for="titre">Titre :</label>
    <input type="text" name="titre" id="titre" value="<?php echo htmlspecialchars($cour['titre']); ?>" required><br><br>

    <label for="description">Description :</label>
    <textarea name="description" id="description" required><?php echo htmlspecialchars($cour['description']); ?></textarea><br><br>

    <label for="image">Image :</label>
    <input type="file" name="image" id="image"><br><br>
    <img src="../../uploads/<?php echo htmlspecialchars($cour['image']); ?>" width="150" alt="Image du cours"><br><br>

    <label for="url">URL :</label>
    <input type="text" name="url" id="url" value="<?php echo htmlspecialchars($cour['url']); ?>" required><br><br>

    <label for="formation_id">Formation :</label>
    <select name="formation_id" id="formation_id" required>
        <?php foreach ($formations as $formation): ?>
            <option value="<?php echo $formation['id']; ?>" <?php if ($formation['id'] == $cour['formation_id']) echo 'selected'; ?>>
                <?php echo htmlspecialchars($formation['titre']); ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <input type="submit" value="Modifier le Cours" class="btn btn-primary">
</form>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>