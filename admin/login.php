<?php

include '../config.php';


$message = "";

// Vérifier si l'utilisateur a trop essayé de se connecter
if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] >= 5) {
    $message = "❌ Trop de tentatives. Réessayez plus tard.";
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];

    // Vérifier si l'email existe dans la base de données
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
        // Réinitialiser les tentatives de connexion
        $_SESSION['login_attempts'] = 0;
        
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        // Redirection selon le rôle
        if ($user['role'] == 'admin') {
            header("Location: index.php");
        } else {
            header("Location: login.php"); // Page pour les utilisateurs normaux
        }
        exit;
    } else {
        // Incrémenter le nombre de tentatives
        $_SESSION['login_attempts'] = ($_SESSION['login_attempts'] ?? 0) + 1;
        $message = "❌ Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../../assets/admin.css">
</head>
<body>

<header>
    <h1>Se connecter</h1>
</header>

<?php if (!empty($message)): ?>
    <p style="color: red;"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<form action="login.php" method="POST">
    <label for="email">Email :</label>
    <input type="email" name="email" id="email" required><br><br>

    <label for="mot_de_passe">Mot de passe :</label>
    <input type="password" name="mot_de_passe" id="mot_de_passe" required><br><br>

    <input type="submit" value="Se connecter">
</form>

</body>
</html>
