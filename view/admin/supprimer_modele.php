<?php
// Inclusion des fichiers nécessaires
require_once __DIR__ . '/../../bdd/bdd.php';
require_once __DIR__ . '/../../controller/VoitureController.php';

// Vérifier si un ID a été fourni
if (isset($_GET['id'])) {
    $idVoiture = intval($_GET['id']);

    // Initialisation du contrôleur avec la connexion BDD
    $voitureController = new VoitureController($bdd);

    // Suppression de la voiture
    $resultat = $voitureController->supprimerVoiture($idVoiture);

    // Vérification du résultat
    if (isset($resultat['error'])) {
        echo "<p>Erreur : " . $resultat['error'] . "</p>";
    } else {
        echo "<p>Voiture supprimée avec succès.</p>";
    }
} else {
    echo "<p>Aucune voiture sélectionnée.</p>";
}
?>
