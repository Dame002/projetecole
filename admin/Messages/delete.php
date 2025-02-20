<?php
include '../../config.php';

// Vérifier si l'ID est passé dans l'URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Supprimer le message de la base de données
    $stmt = $pdo->prepare("DELETE FROM messages WHERE id = :id");
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        header("Location: liste_contact.php?success=Message supprimé avec succès");
    } else {
        header("Location: liste_contact.php?error=Erreur lors de la suppression du message");
    }
}
?>
