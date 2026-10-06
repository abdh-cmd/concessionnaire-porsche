<?php
require_once __DIR__ . '/../bdd/bdd.php'; // Connexion à la BDD

class VoitureModele {
    private $bdd;

    // Constructeur
    public function __construct($bdd) {
        $this->bdd = $bdd;
    }

    /** =====================
     *  RÉCUPÉRATION DES VOITURES
     *  ===================== */

    // Récupérer toutes les voitures
    public function getAllVoitures() {
        try {
            $query = $this->bdd->prepare("SELECT * FROM voitures");
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return ["error" => "Erreur lors de la récupération des voitures : " . $e->getMessage()];
        }
    }

    // Récupérer une voiture par ID
    public function getVoitureById($id) {
        try {
            $query = $this->bdd->prepare("SELECT * FROM voitures WHERE id = :id");
            $query->execute(['id' => $id]);
            return $query->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return ["error" => "Erreur lors de la récupération de la voiture : " . $e->getMessage()];
        }
    }

    /** =====================
     *  GESTION DES VOITURES (AJOUT / MODIF / SUPP)
     *  ===================== */

    // Ajouter une voiture
    public function ajouterVoiture($marque, $modele, $annee, $prix, $image) {
        try {
            $query = $this->bdd->prepare("INSERT INTO voitures (marque, modele, annee, prix, image) VALUES (:marque, :modele, :annee, :prix, :image)");
            return $query->execute([
                'marque' => $marque,
                'modele' => $modele,
                'annee' => $annee,
                'prix' => $prix,
                'image' => $image
            ]);
        } catch (Exception $e) {
            return ["error" => "Erreur lors de l'ajout de la voiture : " . $e->getMessage()];
        }
    }

    // Modifier une voiture
    public function modifierVoiture($id, $marque, $modele, $annee, $prix, $image = null) {
        try {
            // Vérifier si la voiture existe
            if (!$this->getVoitureById($id)) {
                return ["error" => "Erreur : La voiture n'existe pas."];
            }

            $sql = "UPDATE voitures SET marque = :marque, modele = :modele, annee = :annee, prix = :prix";
            $params = ['id' => $id, 'marque' => $marque, 'modele' => $modele, 'annee' => $annee, 'prix' => $prix];

            if ($image) {
                $sql .= ", image = :image";
                $params['image'] = $image;
            }

            $sql .= " WHERE id = :id";
            $query = $this->bdd->prepare($sql);
            return $query->execute($params);
        } catch (Exception $e) {
            return ["error" => "Erreur lors de la modification de la voiture : " . $e->getMessage()];
        }
    }

    // Supprimer une voiture
    public function supprimerVoiture($id) {
        try {
            // Vérifier si la voiture existe
            if (!$this->getVoitureById($id)) {
                return ["error" => "Erreur : La voiture n'existe pas."];
            }

            $query = $this->bdd->prepare("DELETE FROM voitures WHERE id = :id");
            $result = $query->execute(['id' => $id]);

            if ($result) {
                return ["success" => "La voiture a été supprimée avec succès."];
            } else {
                return ["error" => "Erreur lors de la suppression de la voiture."];
            }
        } catch (Exception $e) {
            return ["error" => "Erreur lors de la suppression de la voiture : " . $e->getMessage()];
        }
    }
}
?>
