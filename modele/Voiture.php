<?php

class Voiture {

    private $id;
    private $marque;
    private $modele;
    private $annee;
    private $prix;
    private $image;

    // Constructeur de la classe Voiture
    public function __construct($id, $marque, $modele, $annee, $prix, $image) {
        $this->id = $id;
        $this->marque = $marque;
        $this->modele = $modele;
        $this->annee = $annee;
        $this->prix = $prix;
        $this->image = $image;
    }

    // Getters et setters
    public function getId() {
        return $this->id;
    }

    public function getMarque() {
        return $this->marque;
    }

    public function setMarque($marque) {
        $this->marque = $marque;
    }

    public function getModele() {
        return $this->modele;
    }

    public function setModele($modele) {
        $this->modele = $modele;
    }

    public function getAnnee() {
        return $this->annee;
    }

    public function setAnnee($annee) {
        $this->annee = $annee;
    }

    public function getPrix() {
        return $this->prix;
    }

    public function setPrix($prix) {
        $this->prix = $prix;
    }

    public function getImage() {
        return $this->image;
    }

    public function setImage($image) {
        $this->image = $image;
    }

    // Méthode pour afficher les informations d'une voiture
    public function afficherDetails() {
        return sprintf(
            "Marque: %s, Modèle: %s, Année: %d, Prix: %.2f €, Image: %s",
            $this->marque,
            $this->modele,
            $this->annee,
            $this->prix,
            $this->image
        );
    }

    // Méthode pour insérer une voiture dans la base de données
    public function insererDansBdd($bdd) {
        $query = $bdd->prepare("INSERT INTO voitures (marque, modele, annee, prix, image) VALUES (?, ?, ?, ?, ?)");
        $query->execute([$this->marque, $this->modele, $this->annee, $this->prix, $this->image]);
    }

    // Méthode pour modifier une voiture dans la base de données
    public function modifierDansBdd($bdd) {
        $query = $bdd->prepare("UPDATE voitures SET marque = ?, modele = ?, annee = ?, prix = ?, image = ? WHERE id = ?");
        $query->execute([$this->marque, $this->modele, $this->annee, $this->prix, $this->image, $this->id]);
    }

    // Méthode pour supprimer une voiture de la base de données
    public function supprimerDeBdd($bdd) {
        $query = $bdd->prepare("DELETE FROM voitures WHERE id = ?");
        $query->execute([$this->id]);
    }
}

?>
