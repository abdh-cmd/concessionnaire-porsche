<?php
include_once __DIR__ . '/../bdd/bdd.php';
include_once __DIR__ . '/../controller/PanierController.php';

// Vérification de la connexion de l'utilisateur
if (!isset($_SESSION['user'])) {
    header('Location: index.php?page=login');
    exit();
}

if (!isset($_GET['id']) || !isset($_GET['quantity']) || !is_numeric($_GET['id']) || !is_numeric($_GET['quantity'])) {
    header('Location: index.php?page=panier&error=Paramètres invalides');
    exit();
}

$panierController = new PanierController($bdd);
$result = $panierController->updateQuantity($_SESSION['user']['ID_Utilisateur'], $_GET['id'], $_GET['quantity']);

if ($result) {
    header('Location: index.php?page=panier&message=Quantité mise à jour avec succès');
} else {
    header('Location: index.php?page=panier&error=Erreur lors de la mise à jour de la quantité');
}
exit();