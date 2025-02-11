<?php
// session_start();
// if (!isset($_SESSION['admin'])) {
//     header('Location: ../login.php');
//     exit();
// }
include '../../config.php';

// // Vérification de l'accès admin
// if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
//     header("Location: ../login.php");
//     exit;
// }

// Récupération des cours avec le nom de la formation associée
$sql = "SELECT cours.id, cours.titre, cours.description, formations.titre AS formation 
        FROM cours 
        JOIN formations ON cours.formation_id = formations.id";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$cours = $stmt->fetchAll(PDO::FETCH_ASSOC);



$message=$_GET["success"];
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Cours</title>
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
            <a class="nav-link active" aria-current="page" href="list.php">Cours</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../formations/list.php">Formations</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../create_admin.php">Utilisateurs</a>
          </li>
          <li class="nav-item">
            <a class="nav-link disabled" aria-disabled="true">Disabled</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
    <header>
        <h1>Liste des Cours</h1>
    </header>

    <a href="add.php" >Ajouter un nouveau cours</a>

    <table border="1">
        <tr>
            <th>Titre</th>
            <th>Description</th>
            <th>Formation</th>
            <th>Actions</th>
        </tr>

        <?php if (!empty($cours)): ?>
            <p>
                <?php if ($message): ?>
                    <span class="success"><?php echo $message; ?></span>
                <?php endif; ?>
            </p>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>