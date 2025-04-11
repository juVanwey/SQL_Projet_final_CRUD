<?php
require 'db.php';

// DELETE

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        // $query = "DELETE FROM user WHERE id = $id";
        // $result = $db->query($query);
        $stmt = $db->prepare("DELETE FROM user WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: index.php");
        exit();
    } catch (Exception $e) {
        echo "Erreur lors de la suppression de l'utilisateur : " . $e->getMessage();
    }
} else {
    echo "ID manquant.";
    exit();
}
