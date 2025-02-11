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

// Vérification de l'ID
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: list.php?error=ID invalide");
    exit;
}

$id = (int) $_GET['id']; // Assurer que c'est un entier

// Récupération des données de la formation
try {
    $stmt = $pdo->prepare("SELECT * FROM formations WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $formation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$formation) {
        header("Location: list.php?error=Formation introuvable");
        exit;
    }
} catch (PDOException $e) {
    header("Location: list.php?error=Erreur SQL: " . urlencode($e->getMessage()));
    exit;
}

// Traitement de la mise à jour
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titre = trim($_POST['titre']);
    $description = trim($_POST['description']);

    if (!empty($titre) && !empty($description)) {
        try {
            $stmt = $pdo->prepare("UPDATE formations SET titre = :titre, description = :description WHERE id = :id");
            $stmt->bindValue(':titre', $titre, PDO::PARAM_STR);
            $stmt->bindValue(':description', $description, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            if ($stmt->execute()) {
                header("Location: list.php?success=Formation modifiée avec succès");
                exit;
            } else {
                $error = "Erreur lors de la modification.";
            }
        } catch (PDOException $e) {
            $error = "Erreur SQL: " . $e->getMessage();
        }
    } else {
        $error = "Tous les champs sont obligatoires.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la Formation</title>
    <link rel="stylesheet" href="../../assets/admin.css">
</head>

<body>

    <header>
        <h1>Modifier la Formation</h1>
    </header>

    <?php if (isset($error)) : ?>
        <p style="color: red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <form action="edit.php?id=<?php echo $id; ?>" method="POST">
        <label for="titre">Titre :</label>
        <input type="text" name="titre" id="titre" value="<?php echo htmlspecialchars($formation['titre']); ?>" required><br><br>

        <label for="description">Description :</label>
        <textarea name="description" id="description" required><?php echo htmlspecialchars($formation['description']); ?></textarea><br><br>

        <input type="submit" value="Modifier la Formation">
    </form>

</body>

</html>