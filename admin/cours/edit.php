<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../login.php');
    exit();
}
include '../../config.php';

// Vérification de l'accès admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Vérifier si l'ID du cours est passé en GET
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID du cours manquant.");
}

$id = $_GET['id'];

// Récupérer le cours à modifier
$stmt = $pdo->prepare("SELECT * FROM cours WHERE id = :id");
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$cour = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cour) {
    die("Cours introuvable.");
}

// Récupérer les formations pour le select
$stmt = $pdo->query("SELECT * FROM formations");
$formations = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = $_POST['titre'];
    $description = $_POST['description'];
    $url = $_POST['url'];
    $formation_id = $_POST['formation_id'];

    // Gestion de l'upload d'image (facultatif)
    if (!empty($_FILES['image']['name'])) {
        $image = basename($_FILES['image']['name']);
        $target_dir = "../../uploads/";
        $target_file = $target_dir . $image;

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            $imagePath = $image;
        } else {
            echo "Erreur lors de l'upload de l'image.";
            exit;
        }
    } else {
        $imagePath = $cour['image']; // Garder l'ancienne image si aucun fichier n'est uploadé
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
        header("Location: cours.php?success=Modification réussie");
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
</head>

<body>

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

        <input type="submit" value="Modifier le Cours">
    </form>

</body>

</html>