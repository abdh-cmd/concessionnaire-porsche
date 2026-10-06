<?php
// Inclusion de la connexion à la base de données
include_once __DIR__ . '/../bdd/bdd.php';

class ClientController
{
    private $bdd;

    // Constructeur
    public function __construct($bdd)
    {
        $this->bdd = $bdd;
    }

    // Récupérer tous les clients
    public function getClients()
    {
        try {
            $stmt = $this->bdd->query("SELECT ID_Utilisateur, Nom, Prenom, Email FROM Utilisateurs WHERE Role = 'Client'");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur SQL dans getClients: " . $e->getMessage());
            throw new Exception("Impossible de récupérer les clients.");
        }
    }

    // Récupérer un client spécifique par son ID
    public function getClientById($id)
    {
        if (!is_numeric($id)) {
            throw new Exception("ID invalide.");
        }

        try {
            $stmt = $this->bdd->prepare("SELECT ID_Utilisateur, Nom, Prenom, Email FROM Utilisateurs WHERE ID_Utilisateur = :id AND Role = 'Client'");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $client = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$client) {
                throw new Exception("Client introuvable.");
            }

            return $client;
        } catch (PDOException $e) {
            error_log("Erreur SQL dans getClientById: " . $e->getMessage());
            throw new Exception("Erreur lors de la récupération du client.");
        }
    }

    // Mettre à jour les informations d'un client
    public function updateClient($id, $nom, $prenom, $email)
    {
        if (!is_numeric($id) || empty($nom) || empty($prenom) || empty($email)) {
            throw new Exception("Données invalides.");
        }

        try {
            $stmt = $this->bdd->prepare("
                UPDATE Utilisateurs 
                SET Nom = :nom, Prenom = :prenom, Email = :email 
                WHERE ID_Utilisateur = :id AND Role = 'Client'
            ");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nom', $nom, PDO::PARAM_STR);
            $stmt->bindParam(':prenom', $prenom, PDO::PARAM_STR);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            error_log("Erreur SQL dans updateClient: " . $e->getMessage());
            throw new Exception("Impossible de mettre à jour le client.");
        }
    }

    // Supprimer un client
    public function deleteClient($id)
    {
        if (!is_numeric($id)) {
            throw new Exception("ID invalide.");
        }

        try {
            $stmt = $this->bdd->prepare("DELETE FROM Utilisateurs WHERE ID_Utilisateur = :id AND Role = 'Client'");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            return true;
        } catch (PDOException $e) {
            error_log("Erreur SQL dans deleteClient: " . $e->getMessage());
            throw new Exception("Impossible de supprimer le client.");
        }
    }

    // Récupérer les demandes d'essai d'un client
    public function getDemandesEssaiByClient($id)
    {
        if (!is_numeric($id)) {
            throw new Exception("ID invalide.");
        }

        try {
            $stmt = $this->bdd->prepare("
                SELECT essai.id AS id_essai, voiture.marque, voiture.modele, essai.date_demande, essai.statut
                FROM demandes_essai AS essai
                JOIN voitures AS voiture ON essai.id_voiture = voiture.id
                WHERE essai.id_client = :id
            ");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $demandesEssai = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $demandesEssai;
        } catch (PDOException $e) {
            error_log("Erreur SQL dans getDemandesEssaiByClient: " . $e->getMessage());
            throw new Exception("Erreur lors de la récupération des demandes d'essai.");
        }
    }
}
?>
