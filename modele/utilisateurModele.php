<?php 

class Utilisateur
{
    private $bdd;

    public function __construct($bdd)
    {
        $this->bdd = $bdd;
    }

    /**
     * Ajouter un nouvel utilisateur
     */
    public function ajouterUtilisateur($nom, $prenom, $email, $mdp, $role)
    {
        $role = ucfirst(strtolower($role)); // Transforme 'client' en 'Client' et 'admin' en 'Concess'

        if ($role === "Admin") { 
            $role = "Concess"; 
        }

        $hashPassword = password_hash($mdp, PASSWORD_BCRYPT); // Sécurisation du mot de passe

        $req = $this->bdd->prepare("INSERT INTO utilisateurs (Nom, Prenom, Email, Mdp, Role) 
                                    VALUES (:nom, :prenom, :email, :mdp, :role)");
        $req->bindParam(':nom', $nom);
        $req->bindParam(':prenom', $prenom);
        $req->bindParam(':email', $email);
        $req->bindParam(':mdp', $hashPassword);
        $req->bindParam(':role', $role);

        return $req->execute();
    }

    /**
     * Vérifier l'authentification de l'utilisateur
     */
    public function checkLogin($email, $password)
    {
        $req = $this->bdd->prepare("SELECT * FROM utilisateurs WHERE Email = :email");
        $req->bindParam(':email', $email);
        $req->execute();
        $user = $req->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['Mdp'])) {
            return $user;
        }
        return false;
    }

    /**
     * Récupérer la liste des utilisateurs clients triés par ID
     */
    public function getUtilisateursClients()
    {
        $req = $this->bdd->prepare("SELECT * FROM utilisateurs WHERE Role = 'Client' ORDER BY ID_Utilisateur ASC");
        $req->execute();
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer un utilisateur par son ID
     */
    public function getUtilisateurById($id)
    {
        $req = $this->bdd->prepare("SELECT * FROM utilisateurs WHERE ID_Utilisateur = :id");
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $req->execute();
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Supprimer un utilisateur par son ID
     */
    public function supprimerUtilisateur($id)
    {
        $req = $this->bdd->prepare("DELETE FROM utilisateurs WHERE ID_Utilisateur = :id");
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        return $req->execute();
    }
}
?>
