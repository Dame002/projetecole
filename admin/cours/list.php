<?php
include '../../config.php';

// Récupération des cours avec l'image et l'URL associées
$sql = "SELECT cours.id, cours.titre, cours.description, cours.image, cours.url, formations.titre AS formation 
        FROM cours 
        JOIN formations ON cours.formation_id = formations.id";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$cours = $stmt->fetchAll(PDO::FETCH_ASSOC);

$message = ""; // Valeur par défaut
if (isset($_GET["success"])) {
    $message = $_GET["success"];
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Cours</title>
    <link rel="stylesheet" href="../../assets/admin.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a class="navbar-brand" href="../index.php">Daarul Alam</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false">
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

<div class="container mt-4">
    <header class="mb-3">
        <h1>Liste des Cours</h1>
    </header>

    <a href="add.php" class="btn btn-primary mb-3">Ajouter un cours</a>

    <!-- Affichage des messages de succès ou d'erreur -->
    <?php if (!empty($message)) : ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])) : ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>Image</th>
                <th>Titre</th>
                <th>Description</th>
                <th>Formation</th>
                <th>URL</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cours as $cour): ?>
                <tr>
                    <td>
                        <?php if (!empty($cour['image'])): ?>
                            <img src="uploads/<?php echo htmlspecialchars($cour['image']); ?>" alt="Image du cours" style="max-width: 80px; height: auto;">

                        <?php else: ?>
                            <span class="text-muted">Aucune image</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($cour['titre']); ?></td>
                    <td><?php echo substr(htmlspecialchars($cour['description']), 0, 50) . '...'; ?></td>
                    <td><?php echo htmlspecialchars($cour['formation']); ?></td>
                    <td>
                        <?php if (!empty($cour['url'])): ?>
                            <a href="<?php echo htmlspecialchars($cour['url']); ?>" target="_blank">Voir le cours</a>
                        <?php else: ?>
                            <span class="text-muted">Pas de lien</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="edit.php?id=<?php echo $cour['id']; ?>" class="btn btn-warning btn-sm">Modifier</a>
                        <a href="delete.php?id=<?php echo $cour['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
