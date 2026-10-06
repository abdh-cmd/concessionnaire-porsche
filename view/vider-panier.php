<?php
session_start();
require_once __DIR__ . '/../bdd/bdd.php';
require_once __DIR__ . '/../controller/PanierController.php';

// Vérification de la connexion de l'utilisateur
if (!isset($_SESSION['user'])) {
    header('Location: index.php?page=login');
    exit();
}

try {
    $panierController = new PanierController($bdd);
    $success = $panierController->viderPanier($_SESSION['user']['ID_Utilisateur']);
    
    if ($success) {
        header('Location: index.php?page=panier&message=Le panier a été vidé avec succès');
    } else {
        header('Location: index.php?page=panier&error=Erreur lors de la suppression du panier');
    }
} catch (Exception $e) {
    header('Location: index.php?page=panier&error=' . urlencode($e->getMessage()));
}
exit();
?>