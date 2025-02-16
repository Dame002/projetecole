<?php
// session_start();
// if (!isset($_SESSION['admin'])) {
//     header('Location: ../login.php');
//     exit();
// }
include '../../config.php';


// Vérification de l'accès admin
// if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
//     header("Location: ../login.php");
//     exit;
// }

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
            <a class="nav-link active" aria-current="page" href="../cours/list.php">Cours</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="list.php">Formations</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../livres/list.php">livres</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../auteurs/list.php">Auteurs</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../create_admin.php">Administrateurs</a>
          <!-- </li>
          <li class="nav-item">
            <a class="nav-link disabled" aria-disabled="true">Disabled</a>
          </li> -->
        </ul>
      </div>
    </div>
  </nav>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>