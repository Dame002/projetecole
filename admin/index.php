<?php
// session_start();
session_start();


require '../config.php';
try {
  $stmt = $pdo->query("SELECT * FROM `cours`");
  $cours = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Could not connect to the database $dbname :" . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>


  <nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Daarul Alam</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="cours/list.php">Cours</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="formations/list.php">Formations</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="create_admin.php">Utilisateurs</a>
          </li>
          <li class="nav-item">
            <a class="nav-link disabled" aria-disabled="true">Disabled</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <?php foreach ($cours as $cour): ?>
  <div class="card" style="width: 18rem;">
    <!-- Modifier le chemin d'accès à l'image pour inclure le répertoire uploads/ -->
    <img src="../admin/cours/uploads/<?php echo htmlspecialchars($cour['image']); ?>" class="card-img-top" alt="Image du cours">
    <div class="card-body">
      <h5 class="card-title"><?php echo htmlspecialchars($cour['titre']); ?></h5>
      <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
      <a href="#" class="btn btn-primary">Go somewhere</a>
    </div>
  </div>
<?php endforeach; ?>



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>