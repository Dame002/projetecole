<?php
// session_start();
// if (!isset($_SESSION['admin'])) {
//     header('Location: ../login.php');
//     exit();
// }
include '../../config.php';

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
          <li class="nav-item"><a class="nav-link" href="list.php">Formations</a></li>
          <li class="nav-item"><a class="nav-link" href="../livres/list.php">Livres</a></li>
          <li class="nav-item"><a class="nav-link" href="../auteurs/list.php">Auteurs</a></li>
          <li class="nav-item"><a class="nav-link" href="../create_admin.php">Administrateurs</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container mt-4">
    <header class="mb-3">
      <h1>Liste des Formations</h1>
    </header>

    <a href="add.php" class="btn btn-primary mb-3">Ajouter une formation</a>

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
          <th>Image</th>
          <th>Titre</th>
          <th>Description</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($formations as $formation): ?>
          <tr>
            <td>
              <?php if (!empty($formation['image'])): ?>
                <img src="../../uploads/<?php echo htmlspecialchars($formation['image']); ?>" alt="Image de la formation" width="80" height="50">
              <?php else: ?>
                <img src="../../assets/placeholder.png" alt="Placeholder" width="80" height="50">
              <?php endif; ?>
            </td>
            <td><?php echo htmlspecialchars($formation['titre']); ?></td>
            <td><?php echo substr(htmlspecialchars($formation['description']), 0, 50) . '...'; ?></td>
            <td>
              <a href="edit.php?id=<?php echo $formation['id']; ?>" class="btn btn-warning btn-sm">Modifier</a>
              <a href="delete.php?id=<?php echo $formation['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette formation ?');">Supprimer</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>