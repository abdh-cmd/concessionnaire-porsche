<?php

include_once __DIR__ . '/../bdd/bdd.php';
include_once __DIR__ . '/VoitureController.php';
include_once __DIR__ . '/ConcessionnaireController.php';

class AdminController {
    private $bdd;
    private $voitureController;
    private $concessionnaireController;

    public function __construct($bdd) {
        $this->bdd = $bdd;
        $this->voitureController = new VoitureController($bdd);
        $this->concessionnaireController = new ConcessionnaireController($bdd);
    }

    // Ajouter une voiture
    public function ajouterVoiture($marque, $modele, $annee, $prix, $image) {
        $this->voitureController->ajouterVoiture($marque, $modele, $annee, $prix, $image);
    }

    // Modifier une voiture
    public function modifierVoiture($id, $marque, $modele, $annee, $prix, $image) {
        $this->voitureController->modifierVoiture($id, $marque, $modele, $annee, $prix, $image);
    }

    // Supprimer une voiture
    public function supprimerVoiture($id) {
        $this->voitureController->supprimerVoiture($id);
    }

    // Ajouter un concessionnaire
    public function ajouterConcessionnaire($nom, $email, $motdepasse) {
        $this->concessionnaireController->ajouterConcessionnaire($nom, $email, $motdepasse);
    }

    // Supprimer un concessionnaire
    public function supprimerConcessionnaire($id) {
        $this->concessionnaireController->supprimerConcessionnaire($id);
    }

    // Afficher toutes les voitures
    public function afficherVoitures() {
        return $this->voitureController->afficherVoitures();
    }

    // Afficher tous les concessionnaires
    public function afficherConcessionnaires() {
        return $this->concessionnaireController->afficherConcessionnaires();
    }
}
