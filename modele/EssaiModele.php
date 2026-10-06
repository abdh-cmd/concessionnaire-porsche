<?php
require_once __DIR__ . '/../bdd/bdd.php';

class EssaiModele {
    private $bdd;

    // Constructeur
    public function __construct($bdd) {
        $this->bdd = $bdd;
    }

    // Ajouter une demande d'essai
    public function ajouterEssai($idUtilisateur, $idVoiture) {
        if (!$idUtilisateur || !$idVoiture) {
            return false;
        }

        $query = $this->bdd->prepare("
            INSERT INTO essais (ID_Utilisateur, ID_Voiture, date_demande) 
            VALUES (:idUtilisateur, :idVoiture, NOW())
        ");

        return $query->execute([
            'idUtilisateur' => $idUtilisateur,
            'idVoiture' => $idVoiture
        ]);
    }

    // Récupérer toutes les demandes d'essai
    public function getTousLesEssais() {
        $query = $this->bdd->query("
            SELECT essais.ID_Essai, utilisateurs.nom, utilisateurs.prenom, utilisateurs.email, 
                   voitures.marque, voitures.modele, essais.date_demande
            FROM essais
            JOIN utilisateurs ON essais.ID_Utilisateur = utilisateurs.ID_Utilisateur
            JOIN voitures ON essais.ID_Voiture = voitures.id
            ORDER BY essais.date_demande DESC
        ");

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer les essais d'un utilisateur spécifique
    public function getEssaisParUtilisateur($idUtilisateur) {
        if (!$idUtilisateur) {
            return [];
        }

        $query = $this->bdd->prepare("
            SELECT voitures.marque, voitures.modele, essais.date_demande
            FROM essais
            JOIN voitures ON essais.ID_Voiture = voitures.id
            WHERE essais.ID_Utilisateur = :idUtilisateur
            ORDER BY essais.date_demande DESC
        ");

        $query->execute(['idUtilisateur' => $idUtilisateur]);
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }
}
