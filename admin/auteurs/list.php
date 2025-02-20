<?php
// session_start();
// if (!isset($_SESSION['admin'])) {
//     header('Location: ../login.php');
//     exit();
// }

include '../../config.php';

// Récupération des auteurs avec PDO
try {
    $stmt = $pdo->query("SELECT * FROM auteurs ORDER BY id DESC");
    $auteurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des auteurs</title>
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
                <li class="nav-item"><a class="nav-link active" aria-current="page" href="../cours/list.php">Cours</a></li>
                <li class="nav-item"><a class="nav-link" href="../formations/list.php">Formations</a></li>
                <li class="nav-item">
            <a class="nav-link" href="../livres/list.php">livres</a>
          </li>
                <li class="nav-item"><a class="nav-link" href="list.php">Auteurs</a></li>
                <li class="nav-item">
                        <a class="nav-link" href="../Messages/liste_contact.php">Messages</a>
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
        <h1>Liste des auteurs</h1>
    </header>

    <a href="add.php" class="btn btn-primary mb-3">Ajouter un auteur</a>

    <!-- Affichage des messages de succès ou d'erreur -->
    <?php if (isset($_GET['success'])) : ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_GET['error'])) : ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
    <?php endif; ?>

    <table class="table table-striped">
        <thead class="table-dark">
            <tr>
                <th>Nom</th>
                <th>Image</th>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($auteurs as $auteur): ?>
                <tr>
                    <td><?php echo htmlspecialchars($auteur['nom']); ?></td>
                    <td>
                    <img src="uploads/<?php echo htmlspecialchars($auteur['image']); ?>"

         style="width: 50px; height: 50px; object-fit: cover;" 
         alt="Image de l'auteur">
</td>

                    <td><?php echo substr(htmlspecialchars($auteur['description']), 0, 50) . '...'; ?></td>
                    <td>
                        <a href="edit.php?id=<?php echo $auteur['id']; ?>" class="btn btn-warning btn-sm">Modifier</a>
                        <a href="delete.php?id=<?php echo $auteur['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet auteur ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
