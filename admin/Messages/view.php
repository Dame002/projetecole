<?php
include '../../config.php';

// Récupérer l'ID du message depuis l'URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Récupérer les détails du message
    $stmt = $pdo->prepare("SELECT * FROM messages WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $message = $stmt->fetch(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voir le Message</title>
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
            <h1>Détails du Message</h1>
        </header>

        <?php if ($message): ?>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($message['nom']); ?></h5>
                    <p><strong>Email :</strong> <?php echo htmlspecialchars($message['email']); ?></p>
                    <p><strong>Sujet :</strong> <?php echo htmlspecialchars($message['sujet']); ?></p>
                    <p><strong>Message :</strong> <?php echo nl2br(htmlspecialchars($message['message'])); ?></p>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                Aucun message trouvé avec cet ID.
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>