<?php
include('bdd/bdd.php');

if (!isset($_SESSION['user'])) {
    header('Location: login.php'); // Rediriger vers la page de connexion si non connecté
    exit();
}

$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $id = $user['ID_Utilisateur'];

    $req = $bdd->prepare("UPDATE Utilisateurs SET Nom = :nom, Prenom = :prenom, Email = :email WHERE ID_Utilisateur = :id");
    $req->bindParam(':nom', $nom);
    $req->bindParam(':prenom', $prenom);
    $req->bindParam(':email', $email);
    $req->bindParam(':id', $id);
    
    if ($req->execute()) {
        $_SESSION['user']['Nom'] = $nom;
        $_SESSION['user']['Prenom'] = $prenom;
        $_SESSION['user']['Email'] = $email;
        $message = "Mise à jour réussie !";
    } else {
        $message = "Erreur lors de la mise à jour.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil utilisateur</title>
</head>
<body>
    <h2>Profil de <?php echo htmlspecialchars($user['Nom'] . ' ' . $user['Prenom']); ?></h2>
    
    <?php if (isset($message)) echo "<p>$message</p>"; ?>
    
    <form method="POST">
        <label>Nom :</label>
        <input type="text" name="nom" value="<?php echo htmlspecialchars($user['Nom']); ?>" required>
        
        <label>Prénom :</label>
        <input type="text" name="prenom" value="<?php echo htmlspecialchars($user['Prenom']); ?>" required>
        
        <label>Email :</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['Email']); ?>" required>
        
        <button type="submit">Mettre à jour</button>
    </form>
</body>
</html>