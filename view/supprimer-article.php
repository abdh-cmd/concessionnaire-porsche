<?php
include_once __DIR__ . '/../bdd/bdd.php';
include_once __DIR__ . '/../controller/PanierController.php';

// Vérification de la connexion de l'utilisateur
if (!isset($_SESSION['user'])) {
    header('Location: index.php?page=login');
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php?page=panier&error=Paramètres invalides');
    exit();
}

$panierController = new PanierController($bdd);
$result = $panierController->supprimerArticle($_SESSION['user']['ID_Utilisateur'], $_GET['id']);

if ($result) {
    header('Location: index.php?page=panier&message=Article supprimé avec succès');
} else {
    header('Location: index.php?page=panier&error=Erreur lors de la suppression de l\'article');
}
exit();