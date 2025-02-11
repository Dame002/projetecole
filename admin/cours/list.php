<?php
session_start();
// if (!isset($_SESSION['admin'])) {
//     header('Location: ../login.php');
//     exit();
// }
include '../../config.php';

// Vérification de l'accès admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit;
}

// Récupération des cours avec le nom de la formation associée
$sql = "SELECT cours.id, cours.titre, cours.description, formations.titre AS formation 
        FROM cours 
        JOIN formations ON cours.formation_id = formations.id";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$cours = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Cours</title>
    <link rel="stylesheet" href="../../assets/admin.css">
</head>

<body>

    <header>
        <h1>Liste des Cours</h1>
    </header>

    <table border="1">
        <tr>
            <th>Titre</th>
            <th>Description</th>
            <th>Formation</th>
            <th>Actions</th>
        </tr>

        <?php if (!empty($cours)): ?>
            <?php foreach ($cours as $cour): ?>
                <tr>
                    <td><?php echo htmlspecialchars($cour['titre']); ?></td>
                    <td><?php echo htmlspecialchars($cour['description']); ?></td>
                    <td><?php echo htmlspecialchars($cour['formation']); ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $cour['id']; ?>">Modifier</a>
                        <a href="delete.php?id=<?php echo $cour['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="4">Aucun cours disponible.</td>
            </tr>
        <?php endif; ?>
    </table>

</body>

</html>