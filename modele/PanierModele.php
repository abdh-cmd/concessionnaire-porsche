<?php
// Fichier : modele/PanierModele.php

class PanierModele {
    private $bdd;

    // Constructeur qui initialise la connexion à la base de données
    public function __construct($bdd) {
        $this->bdd = $bdd;
    }

    // Ajouter une voiture au panier pour un utilisateur
    public function ajouterAuPanier($idUtilisateur, $idVoiture) {
        try {
            // Vérifier si la voiture est déjà dans le panier
            $query = "SELECT * FROM panier WHERE id_utilisateur = :idUtilisateur AND id_voiture = :idVoiture";
            $stmt = $this->bdd->prepare($query);
            $stmt->bindParam(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);
            $stmt->bindParam(':idVoiture', $idVoiture, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Si la voiture est déjà dans le panier, augmenter la quantité
                $query = "UPDATE panier SET quantite = quantite + 1 WHERE id_utilisateur = :idUtilisateur AND id_voiture = :idVoiture";
                $stmt = $this->bdd->prepare($query);
                $stmt->bindParam(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);
                $stmt->bindParam(':idVoiture', $idVoiture, PDO::PARAM_INT);
                $stmt->execute();
            } else {
                // Sinon, ajouter une nouvelle entrée avec une quantité initiale de 1
                $query = "INSERT INTO panier (id_utilisateur, id_voiture, quantite) VALUES (:idUtilisateur, :idVoiture, 1)";
                $stmt = $this->bdd->prepare($query);
                $stmt->bindParam(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);
                $stmt->bindParam(':idVoiture', $idVoiture, PDO::PARAM_INT);
                $stmt->execute();
            }
            return ["status" => true, "message" => "Voiture ajoutée au panier avec succès."];
        } catch (Exception $e) {
            return ["error" => "Erreur lors de l'ajout au panier : " . $e->getMessage()];
        }
    }

    // Supprimer une voiture du panier pour un utilisateur
    public function supprimerVoitureDuPanierParUtilisateur($idUtilisateur, $idVoiture) {
        try {
            // Vérifier si la voiture est dans le panier
            $query = "SELECT * FROM panier WHERE id_utilisateur = :idUtilisateur AND id_voiture = :idVoiture";
            $stmt = $this->bdd->prepare($query);
            $stmt->bindParam(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);
            $stmt->bindParam(':idVoiture', $idVoiture, PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $panierItem = $stmt->fetch(PDO::FETCH_ASSOC);
                if ($panierItem['quantite'] > 1) {
                    // Réduire la quantité si elle est supérieure à 1
                    $query = "UPDATE panier SET quantite = quantite - 1 WHERE id_utilisateur = :idUtilisateur AND id_voiture = :idVoiture";
                    $stmt = $this->bdd->prepare($query);
                    $stmt->bindParam(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);
                    $stmt->bindParam(':idVoiture', $idVoiture, PDO::PARAM_INT);
                    $stmt->execute();
                } else {
                    // Sinon, supprimer complètement l'élément
                    $query = "DELETE FROM panier WHERE id_utilisateur = :idUtilisateur AND id_voiture = :idVoiture";
                    $stmt = $this->bdd->prepare($query);
                    $stmt->bindParam(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);
                    $stmt->bindParam(':idVoiture', $idVoiture, PDO::PARAM_INT);
                    $stmt->execute();
                }
                return ["status" => true, "message" => "Voiture supprimée du panier avec succès."];
            } else {
                return ["status" => false, "message" => "Voiture non trouvée dans le panier."];
            }
        } catch (Exception $e) {
            return ["error" => "Erreur lors de la suppression de la voiture du panier : " . $e->getMessage()];
        }
    }

    // Supprimer une voiture du panier (pour tous les utilisateurs) en se basant sur l'ID de la voiture
    public function supprimerVoitureDuPanierParVoiture($idVoiture) {
        try {
            $query = "DELETE FROM panier WHERE id_voiture = :idVoiture";
            $stmt = $this->bdd->prepare($query);
            $stmt->bindParam(':idVoiture', $idVoiture, PDO::PARAM_INT);
            $stmt->execute();
            return ["status" => true, "message" => "Voiture supprimée du panier avec succès."];
        } catch (Exception $e) {
            return ["error" => "Erreur lors de la suppression de la voiture du panier : " . $e->getMessage()];
        }
    }

    // Vider le panier d'un utilisateur
    public function viderPanierUtilisateur($idUtilisateur) {
        try {
            $query = "DELETE FROM panier WHERE id_utilisateur = :idUtilisateur";
            $stmt = $this->bdd->prepare($query);
            $stmt->bindParam(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);
            $stmt->execute();
            return ["status" => true, "message" => "Panier vidé avec succès."];
        } catch (Exception $e) {
            return ["error" => "Erreur lors de la suppression des éléments du panier : " . $e->getMessage()];
        }
    }

    // Récupérer le panier d'un utilisateur
    public function getPanierUtilisateur($idUtilisateur) {
        try {
            $query = "SELECT v.id, v.marque, v.modele, v.image, v.prix, p.quantite
                      FROM panier p
                      JOIN voitures v ON p.id_voiture = v.id
                      WHERE p.id_utilisateur = :idUtilisateur";
            $stmt = $this->bdd->prepare($query);
            $stmt->bindParam(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return ["error" => "Erreur lors de la récupération du panier : " . $e->getMessage()];
        }
    }

    // Demander un essai pour une voiture
    public function demanderEssai($idUtilisateur, $idVoiture) {
        try {
            // Insertion de la demande d'essai dans la table demandes_essai
            $query = "INSERT INTO demandes_essai (id_utilisateur, id_voiture) VALUES (:idUtilisateur, :idVoiture)";
            $stmt = $this->bdd->prepare($query);
            $stmt->bindParam(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);
            $stmt->bindParam(':idVoiture', $idVoiture, PDO::PARAM_INT);
            $stmt->execute();
            return ["status" => true, "message" => "Demande d'essai effectuée avec succès."];
        } catch (Exception $e) {
            return ["error" => "Erreur lors de la demande d'essai : " . $e->getMessage()];
        }
    }
}
?>
