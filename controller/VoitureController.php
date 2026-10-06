<?php
// Fichier : controller/VoitureController.php

// Inclusion des fichiers nécessaires
require_once __DIR__ . '/../modele/VoitureModele.php';
require_once __DIR__ . '/../modele/PanierModele.php';
require_once __DIR__ . '/../bdd/bdd.php';

class VoitureController {
    private $voitureModele;
    private $panierModele;

    // Constructeur qui initialise les modèles avec la connexion à la BDD
    public function __construct($bdd) {
        $this->voitureModele = new VoitureModele($bdd);
        $this->panierModele  = new PanierModele($bdd);
    }

    /** ===================== 
     *  GESTION DES VOITURES 
     *  ===================== */

    // Récupérer toutes les voitures
    public function afficherVoitures() {
        try {
            return $this->voitureModele->getAllVoitures();
        } catch (Exception $e) {
            return ["error" => "Erreur lors de la récupération des voitures : " . $e->getMessage()];
        }
    }

    // Obtenir une voiture par son ID (pour afficher les détails)
    public function getVoitureById($id) {
        try {
            return $this->voitureModele->getVoitureById($id);
        } catch (Exception $e) {
            return ["error" => "Erreur lors de la récupération de la voiture : " . $e->getMessage()];
        }
    }

    // Ajouter une nouvelle voiture en BDD
    public function ajouterVoiture($marque, $modele, $annee, $prix, $image) {
        try {
            return $this->voitureModele->ajouterVoiture($marque, $modele, $annee, $prix, $image);
        } catch (Exception $e) {
            return ["error" => "Erreur lors de l'ajout de la voiture : " . $e->getMessage()];
        }
    }

    // Modifier une voiture existante
    public function modifierVoiture($id, $marque, $modele, $annee, $prix, $image = null) {
        try {
            return $this->voitureModele->modifierVoiture($id, $marque, $modele, $annee, $prix, $image);
        } catch (Exception $e) {
            return ["error" => "Erreur lors de la modification de la voiture : " . $e->getMessage()];
        }
    }

    // Supprimer une voiture (et supprimer aussi ses occurrences dans les paniers)
    public function supprimerVoiture($id) {
        try {
            // Supprimer toutes les entrées du panier liées à cette voiture
            $this->panierModele->supprimerVoitureDuPanierParVoiture($id);
            // Supprimer la voiture de la BDD
            return $this->voitureModele->supprimerVoiture($id);
        } catch (Exception $e) {
            return ["error" => "Erreur lors de la suppression de la voiture : " . $e->getMessage()];
        }
    }

    /** ===================== 
     *  GESTION DU PANIER 
     *  ===================== */

    // Ajouter une voiture au panier
    public function ajouterAuPanier($idUtilisateur, $idVoiture) {
        try {
            return $this->panierModele->ajouterAuPanier($idUtilisateur, $idVoiture);
        } catch (Exception $e) {
            return ["error" => "Erreur lors de l'ajout au panier : " . $e->getMessage()];
        }
    }

    // Supprimer une voiture du panier par l'ID de la voiture et l'ID de l'utilisateur
    public function supprimerVoitureDuPanier($idUtilisateur, $idVoiture) {
        try {
            return $this->panierModele->supprimerVoitureDuPanierParUtilisateur($idUtilisateur, $idVoiture);
        } catch (Exception $e) {
            return ["error" => "Erreur lors de la suppression de la voiture du panier : " . $e->getMessage()];
        }
    }

    /** ===================== 
     *  GESTION DES DEMANDES D'ESSAI 
     *  ===================== */

    // La demande d'essai doit être gérée via EssaiController
    // On ne définit pas ici la méthode de demande d'essai.
}
?>
