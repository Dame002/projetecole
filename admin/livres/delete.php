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

        // Vérifier si le livre existe avant de supprimer (récupère l'image et le PDF)
        $checkSql = "SELECT image, pdf FROM livres WHERE id = :id";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->bindParam(':id', $id, PDO::PARAM_INT);
        $checkStmt->execute();
        $livre = $checkStmt->fetch(PDO::FETCH_ASSOC);

        if ($livre) {
            // Supprimer l'image associée si elle existe
            if (!empty($livre['image']) && file_exists('../../uploads/' . $livre['image'])) {
                unlink('../../uploads/' . $livre['image']);
            }
            
            // Supprimer le PDF associé s'il existe
            if (!empty($livre['pdf']) && file_exists('../../uploads/' . $livre['pdf'])) {
                unlink('../../uploads/' . $livre['pdf']);
            }

            // Préparer la requête pour supprimer le livre
            $sql = "DELETE FROM livres WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);

            // Exécuter la requête
            if ($stmt->execute()) {
                header("Location: list.php?success=Livre supprimé avec succès.");
                exit();
            } else {
                echo "Erreur lors de la suppression du livre.";
            }
        } else {
            echo "Livre introuvable.";
        }
    } else {
        echo "ID invalide.";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
