<?php
// session_start();
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

// Vérification de l'ID
if (!isset($_GET['id']) || empty($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: list.php?error=ID invalide");
    exit;
}

$id = (int) $_GET['id']; // Assurer que c'est un entier

try {
    // Suppression de la formation
    $stmt = $pdo->prepare("DELETE FROM formations WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);

    if ($stmt->execute()) {
        header("Location: list.php?success=Formation supprimée avec succès");
        exit;
    } else {
        header("Location: list.php?error=Erreur lors de la suppression");
        exit;
    }
} catch (PDOException $e) {
    header("Location: list.php?error=Erreur SQL: " . urlencode($e->getMessage()));
    exit;
}
