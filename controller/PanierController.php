<?php
require_once __DIR__ . '/../bdd/bdd.php';
require_once __DIR__ . '/../modele/PanierModele.php';

class PanierController {
    private $panierModele;

    // Constructeur
    public function __construct($bdd) {
        $this->panierModele = new PanierModele($bdd);
    }

    // Ajouter une voiture au panier
    public function ajouterAuPanier($idUtilisateur, $idVoiture) {
        if (empty($idUtilisateur) || empty($idVoiture)) {
            return ['status' => false, 'message' => 'Données invalides.'];
        }

        return $this->panierModele->ajouterAuPanier($idUtilisateur, $idVoiture);
    }

    // Supprimer une voiture du panier (1 par 1)
    public function supprimerVoitureDuPanier($idUtilisateur, $idVoiture) {
        if (empty($idUtilisateur) || empty($idVoiture)) {
            return ['status' => false, 'message' => 'Données invalides.'];
        }

        return $this->panierModele->supprimerVoitureDuPanier($idUtilisateur, $idVoiture);
    }

    // Vider entièrement le panier d'un utilisateur
    public function viderPanierUtilisateur($idUtilisateur) {
        if (empty($idUtilisateur)) {
            return ['status' => false, 'message' => 'Utilisateur non trouvé.'];
        }

        return $this->panierModele->viderPanierUtilisateur($idUtilisateur);
    }

    // Récupérer le panier d'un utilisateur
    public function afficherPanier($idUtilisateur) {
        if (empty($idUtilisateur)) {
            return ['status' => false, 'message' => 'Utilisateur non trouvé.'];
        }

        // Récupérer le panier de l'utilisateur dans le modèle
        $panier = $this->panierModele->getPanierUtilisateur($idUtilisateur);
        return ['status' => true, 'data' => $panier];
    }

    // Demander un essai pour une voiture
    public function demanderEssai($idUtilisateur, $idVoiture) {
        if (empty($idUtilisateur) || empty($idVoiture)) {
            return ['status' => false, 'message' => 'Données invalides.'];
        }

        return $this->panierModele->demanderEssai($idUtilisateur, $idVoiture);
    }
}
?>
