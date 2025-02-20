<?php
include '../../config.php';


// Récupérer tous les messages depuis la base de données
$sql = "SELECT * FROM messages";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <title>Liste des Messages</title>
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

                    <li class="nav-item"><a class="nav-link" href="../cours/list.php">Cours</a></li>
                    <li class="nav-item"><a class="nav-link" href="../formations/list.php">Formations</a></li>
                    <li class="nav-item"><a class="nav-link" href="../livres/list.php">Livres</a></li>
                    <li class="nav-item"><a class="nav-link" href="../auteurs/list.php">Auteurs</a></li>
                    <li class="nav-item"><a class="nav-link active" href="liste_contact.php">Messages</a></li>
                    <li class="nav-item"><a class="nav-link" href="../create_admin.php">Administrateurs</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <header class="mb-3">
            <h1>Liste des Messages</h1>
        </header>

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
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Sujet</th>
                    <th>Message</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $message): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($message['nom']); ?></td>
                        <td><?php echo htmlspecialchars($message['email']); ?></td>
                        <td><?php echo htmlspecialchars($message['sujet']); ?></td>
                        <td><?php echo htmlspecialchars($message['message']) ?></td>
                        <td>
                            <!-- Ajouter des actions comme modifier ou supprimer -->
                            <a href="view.php?id=<?php echo $message['id']; ?>" class="btn btn-info btn-sm">Voir</a>
                            <a href="delete.php?id=<?php echo $message['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>