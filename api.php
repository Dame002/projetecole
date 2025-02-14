<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include "config.php";

try {
    // Requête SQL
    $sql = "SELECT id, titre, description, image, url FROM cours";
    $stmt = $pdo->query($sql);
    
    $cours = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'cours' => $cours
    ]);

} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erreur de base de données : ' . $e->getMessage()
    ]);
}
?>