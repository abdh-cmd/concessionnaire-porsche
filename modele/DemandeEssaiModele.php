<?php

class DemandeEssaiModele
{
    private $bdd;

    // Constructeur qui prend en paramètre la connexion à la base de données
    public function __construct($bdd)
    {
        $this->bdd = $bdd;
    }

    /**
     * Créer une demande d'essai pour un utilisateur
     */
    public function creerDemandeEssai($id_utilisateur, $id_voiture, $permis_obtenu)
    {
        try {
            // Préparer et exécuter la requête SQL pour insérer une nouvelle demande d'essai
            $stmt = $this->bdd->prepare("INSERT INTO DemandeEssai (id_utilisateur, id_voiture, permis_obtenu, statut) 
                                         VALUES (:id_utilisateur, :id_voiture, :permis_obtenu, 'En attente')");
            $stmt->bindParam(':id_utilisateur', $id_utilisateur, PDO::PARAM_INT);
            $stmt->bindParam(':id_voiture', $id_voiture, PDO::PARAM_INT);
            $stmt->bindParam(':permis_obtenu', $permis_obtenu, PDO::PARAM_BOOL);
            $stmt->execute();
            return ["status" => true, "message" => "Demande d'essai créée avec succès."];
        } catch (Exception $e) {
            return ["status" => false, "message" => "Erreur lors de la création de la demande : " . $e->getMessage()];
        }
    }
}

?>
