<?php
// Configuration de la connexion à la base de données
$host = 'localhost';
$dbname = 'porsche2';
$user = 'root';  // Utilisateur de la base de données
$pass = '';      // Mot de passe de la base de données

// Options PDO pour une connexion sécurisée et optimisée
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Active les exceptions en cas d'erreur SQL
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Mode de récupération : tableau associatif
    PDO::ATTR_EMULATE_PREPARES => false, // Désactive l'émulation des requêtes préparées pour éviter les injections SQL
    PDO::ATTR_PERSISTENT => true // Active la connexion persistante pour de meilleures performances
];

// Connexion à la base de données avec gestion des erreurs
try {
    // Connexion PDO avec les options définies
    $bdd = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass, $options);
    // Vous pouvez ajouter un message de débogage si nécessaire pour valider la connexion (en local uniquement)
    // echo "Connexion réussie à la base de données";
} catch (PDOException $e) {
    // Journaliser l'erreur plutôt que de l'afficher (remplacez par un vrai fichier de logs en production)
    error_log("Erreur de connexion à la base de données : " . $e->getMessage(), 0);
    
    // Affichage d'un message générique sans donner d'infos sensibles (optionnel selon votre configuration)
    die("Une erreur est survenue lors de la connexion à la base de données.");
}
?>
