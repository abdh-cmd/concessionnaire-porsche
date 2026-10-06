<?php
require_once __DIR__ . '/../bdd/bdd.php';
require_once __DIR__ . '/../modele/DemandeEssaiModele.php';

class DemandeEssaiController
{
    private $bdd;
    private $demandeEssaiModele;

    public function __construct($bdd)
    {
        // Initialisation de la connexion à la base de données et du modèle
        $this->bdd = $bdd;
        $this->demandeEssaiModele = new DemandeEssaiModele($bdd);
    }

    // Créer une demande d'essai
    public function creerDemandeEssai($id_utilisateur, $id_voiture, $permis_obtenu)
    {
        // Appel de la méthode du modèle pour créer une demande
        return $this->demandeEssaiModele->creerDemandeEssai($id_utilisateur, $id_voiture, $permis_obtenu);
    }

    // Récupérer toutes les demandes d'essai en attente
    public function afficherDemandesEnAttente()
    {
        // Appel de la méthode du modèle pour récupérer les demandes en attente
        return $this->demandeEssaiModele->afficherDemandesEnAttente();
    }

    // Traiter une demande d'essai (accepter ou refuser)
    public function traiterDemande($id_demande, $statut)
    {
        // Appel de la méthode du modèle pour traiter une demande
        return $this->demandeEssaiModele->traiterDemande($id_demande, $statut);
    }
}
?>
