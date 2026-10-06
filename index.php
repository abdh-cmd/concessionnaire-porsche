<?php
// index.php

// Démarrer la session uniquement si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclusion du header
include('view/commun/header.php');

// Déterminer la page à afficher, avec 'accueil' par défaut
$page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? 'accueil';

// Vérifier si l'utilisateur est connecté et récupérer son rôle
$utilisateur = $_SESSION['user'] ?? null;
$roleUtilisateur = $utilisateur['Role'] ?? null;
$demandeEssai = $utilisateur['demande_essai'] ?? false; // Vérification si un essai a été demandé

// Fonction pour inclure une vue et gérer les erreurs d'inclusion
function includeView($viewPath) {
    if (file_exists($viewPath)) {
        include($viewPath);
    } else {
        echo "<p>Erreur : La page demandée est introuvable.</p>";
    }
}

// Switch pour rediriger vers les bonnes vues
switch ($page) {
    case 'login':
        includeView('view/login.php');
        break;

    case 'inscription':
        includeView('view/inscription.php');
        break;

    case 'panier':
        includeView('view/panier.php');
        break;

    case 'modeles':
        includeView('view/modeles.php');
        break;

    case 'demande_essai': // Demande d'essai pour le client
        if ($roleUtilisateur === 'Client') {
            includeView('view/demande_essai.php');
        } else {
            echo "<p>Accès refusé. Seuls les clients peuvent faire une demande d'essai.</p>";
        }
        break;

    case 'page_essais':
        includeView('view/page_essais.php');
        break;

    case 'gerer_clients': // Gestion des clients (accès réservé aux concessionnaires)
        if ($roleUtilisateur === 'Concess') {
            includeView('view/admin/gerer_clients.php');
        } else {
            echo "<p>Accès refusé. Seuls les concessionnaires peuvent accéder à cette page.</p>";
        }
        break;

    case 'gerer_modeles':
    case 'ajouter_modeles':
    case 'modifier_modele':
    case 'supprimer_modele':
        if ($roleUtilisateur === 'Concess') {
            includeView("view/admin/$page.php");
        } else {
            echo "<p>Accès refusé. Seuls les concessionnaires peuvent accéder à cette page.</p>";
        }
        break;

    case 'profil':
        includeView('view/profil.php');
        break;

    case 'details_voiture':
        // Vérifier si un ID valide est fourni
        $idVoiture = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($idVoiture) {
            includeView('view/details/details_voiture.php');
        } else {
            echo "<p>Erreur : Aucun véhicule sélectionné.</p>";
        }
        break;

    case 'stats':
        // Accès réservé aux concessionnaires pour voir les statistiques
        if ($roleUtilisateur === 'Concess') {
            includeView('view/stats.php');
        } else {
            echo "<p>Accès refusé. Seuls les concessionnaires peuvent accéder aux statistiques.</p>";
        }
        break;

    case 'deconnexion':
        // Détruire la session et rediriger vers la page d'accueil
        session_destroy();
        header('Location: index.php');
        exit();

    default:
        // Page d'accueil par défaut
        includeView('view/accueil.php');
        break;
}

// Inclusion du footer
include('view/commun/footer.php');
?>
