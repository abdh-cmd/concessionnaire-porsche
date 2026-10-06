<?php
// Fichier : model/StatModel.php

// Inclusion du fichier de connexion à la BDD
require_once __DIR__ . '/../bdd/bdd.php';

// Vérifier si la variable globale $bdd existe et n'est pas null, sinon créer la connexion manuellement
if (!isset($bdd) || !$bdd) {
    try {
        // Remplacez "your_username" et "your_password" par vos identifiants réels
        $bdd = new PDO("mysql:host=localhost;dbname=porsche2;charset=utf8", "root", "");
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}

class StatModel {
    private $bdd;

    public function __construct() {
        global $bdd;
        if (!$bdd) {
            try {
                // Remplacez "your_username" et "your_password" par vos identifiants réels
                $bdd = new PDO("mysql:host=localhost;dbname=porsche2;charset=utf8", "root", "");
                $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Erreur de connexion : " . $e->getMessage());
            }
        }
        $this->bdd = $bdd;
    }

    public function getClientStats() {
        $stmt = $this->bdd->prepare("
            SELECT u.ID_Utilisateur, u.nom, u.prenom, COUNT(de.ID_Demande) AS nb_essais
            FROM DemandeEssai de
            JOIN Utilisateurs u ON de.ID_Utilisateur = u.ID_Utilisateur
            GROUP BY u.ID_Utilisateur, u.nom, u.prenom
            ORDER BY nb_essais DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getVoitureStats() {
        $stmt = $this->bdd->prepare("
            SELECT v.id, v.marque, v.modele, COUNT(de.ID_Demande) AS nb_essais
            FROM DemandeEssai de
            JOIN Voitures v ON de.ID_Voiture = v.id
            GROUP BY v.id, v.marque, v.modele
            ORDER BY nb_essais DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
