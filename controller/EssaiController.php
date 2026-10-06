<?php
// Fichier : controller/EssaiController.php
require_once __DIR__ . '/../bdd/bdd.php';

class EssaiController {
    private $bdd;

    public function __construct($bdd) {
        $this->bdd = $bdd;
    }

    // Demander un essai (insertion dans la table demandeessai)
    public function demanderEssai($idUtilisateur, $idVoiture, $dateVoulue, $finValiditePermis, $anneePermis, $accompagnant) {
        if (!$idUtilisateur || !$idVoiture || !$dateVoulue || !$finValiditePermis || !$anneePermis) {
            return ['success' => false, 'message' => 'Données invalides.'];
        }
        try {
            $query = $this->bdd->prepare("
                INSERT INTO demandeessai 
                    (ID_Utilisateur, ID_Voiture, date_demande, date_voulue, fin_validite_permis, annee_permis, accompagnant, statut)
                VALUES 
                    (:idUtilisateur, :idVoiture, NOW(), :dateVoulue, :finValiditePermis, :anneePermis, :accompagnant, 'En attente')
            ");
            $result = $query->execute([
                'idUtilisateur'      => $idUtilisateur,
                'idVoiture'          => $idVoiture,
                'dateVoulue'         => $dateVoulue,
                'finValiditePermis'  => $finValiditePermis,
                'anneePermis'        => $anneePermis,
                'accompagnant'       => !empty($accompagnant) ? $accompagnant : null
            ]);
            return $result 
                ? ['success' => true, 'message' => 'Votre demande d\'essai a bien été envoyée.']
                : ['success' => false, 'message' => 'Erreur lors de l\'enregistrement de la demande.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur SQL : ' . $e->getMessage()];
        }
    }

    // Récupérer toutes les demandes d'essai pour le concessionnaire (depuis la table demandeessai)
    public function getEssaisPourConcess() {
        try {
            $query = $this->bdd->query("
                SELECT demandeessai.ID_Demande, utilisateurs.ID_Utilisateur, utilisateurs.nom, utilisateurs.prenom, 
                       voitures.marque, voitures.modele, demandeessai.date_demande, demandeessai.date_voulue,
                       demandeessai.fin_validite_permis, demandeessai.annee_permis, demandeessai.accompagnant, demandeessai.statut
                FROM demandeessai
                JOIN utilisateurs ON demandeessai.ID_Utilisateur = utilisateurs.ID_Utilisateur
                JOIN voitures ON demandeessai.ID_Voiture = voitures.id
                ORDER BY demandeessai.date_demande DESC
            ");
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    // Traiter une demande d'essai (accepter ou refuser)
    public function traiterDemande($idDemande, $statut) {
        if (!in_array($statut, ['Acceptee', 'Refusee'])) {
            return ['success' => false, 'message' => 'Statut invalide.'];
        }
        try {
            $query = $this->bdd->prepare("UPDATE demandeessai SET statut = :statut WHERE ID_Demande = :idDemande");
            $result = $query->execute([
                'statut'    => $statut,
                'idDemande' => $idDemande
            ]);
            if ($statut === 'Acceptee') {
                $this->deplacerEssaiDansTable($idDemande);
            }
            return $result 
                ? ['success' => true, 'message' => 'Statut mis à jour avec succès.']
                : ['success' => false, 'message' => 'Erreur lors de la mise à jour.'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Erreur SQL : ' . $e->getMessage()];
        }
    }

    // Déplacer une demande acceptée dans la table essai et supprimer la demande de demandeessai
    private function deplacerEssaiDansTable($idDemande) {
        try {
            $query = $this->bdd->prepare("SELECT * FROM demandeessai WHERE ID_Demande = :idDemande");
            $query->execute(['idDemande' => $idDemande]);
            $demande = $query->fetch(PDO::FETCH_ASSOC);
            if ($demande) {
                $queryInsert = $this->bdd->prepare("
                    INSERT INTO essai 
                        (ID_Utilisateur, ID_Voiture, date_demande, date_voulue, fin_validite_permis, annee_permis, accompagnant, statut)
                    VALUES 
                        (:idUtilisateur, :idVoiture, :dateDemande, :dateVoulue, :finValiditePermis, :anneePermis, :accompagnant, 'Validée')
                ");
                $resultInsert = $queryInsert->execute([
                    'idUtilisateur'      => $demande['ID_Utilisateur'],
                    'idVoiture'          => $demande['ID_Voiture'],
                    'dateDemande'        => $demande['date_demande'],
                    'dateVoulue'         => $demande['date_voulue'],
                    'finValiditePermis'  => $demande['fin_validite_permis'],
                    'anneePermis'        => $demande['annee_permis'],
                    'accompagnant'       => $demande['accompagnant']
                ]);
                if ($resultInsert) {
                    $queryDelete = $this->bdd->prepare("DELETE FROM demandeessai WHERE ID_Demande = :idDemande");
                    $queryDelete->execute(['idDemande' => $idDemande]);
                }
            }
        } catch (PDOException $e) {
            // Vous pouvez loguer l'erreur ici si nécessaire
        }
    }
}
?>
