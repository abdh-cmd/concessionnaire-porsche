<?php
require_once __DIR__ . '/../bdd/bdd.php';

class ModelesController
{
    private $bdd;

    public function __construct($bdd)
    {
        $this->bdd = $bdd;
    }

    // Récupérer tous les modèles de voiture
    public function getModeles()
    {
        $query = $this->bdd->prepare("SELECT * FROM voitures");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer un modèle de voiture par son ID
    public function getModeleById($id)
    {
        $query = $this->bdd->prepare("SELECT * FROM voitures WHERE id = :id");
        $query->execute(['id' => $id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    // Ajouter un nouveau modèle de voiture
    public function ajouterModele($marque, $modele, $annee, $prix, $image, $puissance_ch, $acceleration_0_100, $vitesse_max, $description)
    {
        // Vérifier si une image a été fournie et la déplacer dans le bon dossier
        $imagePath = null;
        if ($image['error'] === 0) {
            $imagePath = 'images/' . basename($image['name']);
            move_uploaded_file($image['tmp_name'], $imagePath);
        }

        // Préparer la requête SQL d'insertion
        $query = $this->bdd->prepare("INSERT INTO voitures 
            (marque, modele, annee, prix, image, puissance_ch, acceleration_0_100, vitesse_max, description) 
            VALUES (:marque, :modele, :annee, :prix, :image, :puissance_ch, :acceleration_0_100, :vitesse_max, :description)");

        // Exécuter la requête
        return $query->execute([
            'marque' => $marque,
            'modele' => $modele,
            'annee' => $annee,
            'prix' => $prix,
            'image' => $imagePath,  // Utilisation du chemin de l'image
            'puissance_ch' => $puissance_ch,
            'acceleration_0_100' => $acceleration_0_100,
            'vitesse_max' => $vitesse_max,
            'description' => $description
        ]);
    }

    // Modifier un modèle de voiture existant
    public function modifierModele($id, $marque, $modele, $annee, $prix, $image, $puissance_ch, $acceleration_0_100, $vitesse_max, $description)
    {
        // Si une nouvelle image est envoyée, on la traite
        if ($image['error'] === 0) {
            $imagePath = 'images/' . basename($image['name']);
            move_uploaded_file($image['tmp_name'], $imagePath);
        } else {
            // Conserver l'image actuelle si aucune nouvelle image n'est envoyée
            $imagePath = $this->getModeleById($id)['image'];
        }

        // Préparer la requête SQL de mise à jour
        $query = $this->bdd->prepare("UPDATE voitures 
                                      SET marque = :marque, modele = :modele, annee = :annee, prix = :prix, 
                                          image = :image, puissance_ch = :puissance_ch, acceleration_0_100 = :acceleration_0_100, 
                                          vitesse_max = :vitesse_max, description = :description
                                      WHERE id = :id");

        // Exécuter la requête
        return $query->execute([
            'id' => $id,
            'marque' => $marque,
            'modele' => $modele,
            'annee' => $annee,
            'prix' => $prix,
            'image' => $imagePath,  // Utilisation du chemin d'image
            'puissance_ch' => $puissance_ch,
            'acceleration_0_100' => $acceleration_0_100,
            'vitesse_max' => $vitesse_max,
            'description' => $description
        ]);
    }

    // Supprimer un modèle de voiture
    public function supprimerModele($id)
    {
        // Préparer et exécuter la requête SQL de suppression
        $query = $this->bdd->prepare("DELETE FROM voitures WHERE id = :id");
        $result = $query->execute(['id' => $id]);

        // Si la suppression est réussie, on retourne vrai
        return $result;
    }
}
