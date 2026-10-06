<?php

include_once __DIR__ . '/../models/Voiture.php';
include_once __DIR__ . '/../bdd/bdd.php'; // Assurez-vous d'inclure votre fichier de connexion à la base de données

class ConcessionnaireController {

    private $bdd;

    // Constructeur pour l'injection de la base de données
    public function __construct($bdd) {
        $this->bdd = $bdd;
    }

    // Méthode pour ajouter une voiture
    public function ajouterVoiture($marque, $modele, $annee, $prix, $image) {
        // Validation des entrées
        if (empty($marque) || empty($modele) || empty($annee) || !is_numeric($prix) || !filter_var($image, FILTER_VALIDATE_URL)) {
            throw new Exception("Données invalides pour l'ajout de la voiture.");
        }

        // Créer une nouvelle instance de Voiture
        $voiture = new Voiture(null, $marque, $modele, $annee, $prix, $image);

        // Insérer la voiture dans la base de données
        try {
            $voiture->insererDansBdd($this->bdd);
        } catch (Exception $e) {
            error_log("Erreur lors de l'ajout de la voiture: " . $e->getMessage());
            throw new Exception("Impossible d'ajouter la voiture.");
        }
    }

    // Méthode pour afficher la liste des voitures
    public function afficherVoitures() {
        try {
            $query = $this->bdd->query("SELECT * FROM voitures");
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de l'affichage des voitures: " . $e->getMessage());
            throw new Exception("Impossible de récupérer les voitures.");
        }
    }

    // Méthode pour modifier les détails d'une voiture
    public function modifierVoiture($id, $marque, $modele, $annee, $prix, $image) {
        // Validation des entrées
        if (!is_numeric($id) || empty($marque) || empty($modele) || empty($annee) || !is_numeric($prix) || !filter_var($image, FILTER_VALIDATE_URL)) {
            throw new Exception("Données invalides pour la modification de la voiture.");
        }

        // Créer une nouvelle instance de Voiture avec l'ID
        $voiture = new Voiture($id, $marque, $modele, $annee, $prix, $image);

        // Modifier les détails de la voiture dans la base de données
        try {
            $voiture->modifierDansBdd($this->bdd);
        } catch (Exception $e) {
            error_log("Erreur lors de la modification de la voiture: " . $e->getMessage());
            throw new Exception("Impossible de modifier les détails de la voiture.");
        }
    }

    // Méthode pour supprimer une voiture
    public function supprimerVoiture($id) {
        // Validation de l'ID de la voiture
        if (!is_numeric($id)) {
            throw new Exception("ID de la voiture invalide.");
        }

        // Créer une instance de Voiture avec l'ID de la voiture à supprimer
        $voiture = new Voiture($id, '', '', 0, 0, '');

        // Supprimer la voiture de la base de données
        try {
            $voiture->supprimerDeBdd($this->bdd);
        } catch (Exception $e) {
            error_log("Erreur lors de la suppression de la voiture: " . $e->getMessage());
            throw new Exception("Impossible de supprimer la voiture.");
        }
    }

    // Méthode pour afficher une seule voiture (détails)
    public function afficherDetailsVoiture($id) {
        // Validation de l'ID
        if (!is_numeric($id)) {
            throw new Exception("ID de la voiture invalide.");
        }

        try {
            $query = $this->bdd->prepare("SELECT * FROM voitures WHERE id = ?");
            $query->execute([$id]);
            $voiture = $query->fetch(PDO::FETCH_ASSOC);

            if (!$voiture) {
                throw new Exception("Voiture introuvable.");
            }

            return $voiture;
        } catch (PDOException $e) {
            error_log("Erreur lors de l'affichage des détails de la voiture: " . $e->getMessage());
            throw new Exception("Impossible de récupérer les détails de la voiture.");
        }
    }
}

?>
