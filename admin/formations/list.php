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

// Récupération des formations avec PDO
try {
    $stmt = $pdo->query("SELECT * FROM formations ORDER BY id DESC");
    $formations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Formations</title>
    <link rel="stylesheet" href="../../assets/admin.css">
</head>

<body>

    <header>
        <h1>Liste des Formations</h1>
        <a href="add.php" class="btn">Ajouter une Formation</a>
    </header>

    <!-- Affichage des messages de succès ou d'erreur -->
    <?php if (isset($_GET['success'])) : ?>
        <p style="color: green;"><?php echo htmlspecialchars($_GET['success']); ?></p>
    <?php endif; ?>
    <?php if (isset($_GET['error'])) : ?>
        <p style="color: red;"><?php echo htmlspecialchars($_GET['error']); ?></p>
    <?php endif; ?>

    <table>
        <tr>
            <th>Titre</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($formations as $formation): ?>
            <tr>
                <td><?php echo htmlspecialchars($formation['titre']); ?></td>
                <td><?php echo substr(htmlspecialchars($formation['description']), 0, 50) . '...'; ?></td>
                <td>
                    <a href="edit.php?id=<?php echo $formation['id']; ?>" class="btn btn-edit">Modifier</a>
                    <a href="delete.php?id=<?php echo $formation['id']; ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette formation ?');">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

</body>

</html>