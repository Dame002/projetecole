<?php
// session_start();
// if (!isset($_SESSION['admin'])) {
//     header('Location: ../login.php');
//     exit();
// }
// Inclure la configuration et la connexion à la base de données
include '../../config.php';

try {
    // Vérifier si l'ID est passé dans l'URL
    if (isset($_GET['id']) && !empty($_GET['id'])) {
        $id = $_GET['id'];

        // Vérifier si le cours existe avant de supprimer
        $checkSql = "SELECT image FROM cours WHERE id = :id";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->bindParam(':id', $id, PDO::PARAM_INT);
        $checkStmt->execute();
        $cours = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($cours) {
            // Supprimer l'image associée si elle existe
            if (!empty($cours['image']) && file_exists('../../uploads/' . $cours['image'])) {
                unlink('../../uploads/' . $cours['image']);
            }

            // Préparer la requête pour supprimer le cours
            $sql = "DELETE FROM cours WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Exécuter la requête
            if ($stmt->execute()) {
                // Rediriger vers la liste des cours avec un message de succès
                header("Location: list.php?success=Cours supprimé avec succès.");
                exit();
            } else {
                echo "Erreur lors de la suppression du cours.";
            }
        } else {
            echo "Cours introuvable.";
        }
    } else {
        echo "ID invalide.";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
