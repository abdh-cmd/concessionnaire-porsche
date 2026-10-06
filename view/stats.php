<?php
require_once __DIR__ . '/../controller/StatController.php';

// Vérifier que l'utilisateur est connecté et a le rôle "Concess"
if (!isset($_SESSION['user']) || $_SESSION['user']['Role'] !== 'Concess') {
    echo "<p>Accès refusé. Seuls les concessionnaires peuvent accéder à cette page.</p>";
    exit();
}

$statController = new StatController();
$clientStats = $statController->getClientStats();
$voitureStats = $statController->getVoitureStats();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistiques - Concessionnaire</title>
    <link rel="stylesheet" href="css/stats.css"> <!-- Personnalisez ce CSS -->
</head>
<body>
    <h1>Statistiques</h1>
    
    <h2>Récapitulatif des essais par client</h2>
    <?php if (!empty($clientStats)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID Client</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Nombre d'essais</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientStats as $client): ?>
                    <tr>
                        <td><?= htmlspecialchars($client['ID_Utilisateur']) ?></td>
                        <td><?= htmlspecialchars($client['nom']) ?></td>
                        <td><?= htmlspecialchars($client['prenom']) ?></td>
                        <td><?= htmlspecialchars($client['nb_essais']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucun essai enregistré par client.</p>
    <?php endif; ?>

    <h2>Classement des voitures par nombre d'essais</h2>
    <?php if (!empty($voitureStats)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID Voiture</th>
                    <th>Marque</th>
                    <th>Modèle</th>
                    <th>Nombre d'essais</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($voitureStats as $voiture): ?>
                    <tr>
                        <td><?= htmlspecialchars($voiture['id']) ?></td>
                        <td><?= htmlspecialchars($voiture['marque']) ?></td>
                        <td><?= htmlspecialchars($voiture['modele']) ?></td>
                        <td><?= htmlspecialchars($voiture['nb_essais']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune demande d'essai enregistrée pour les voitures.</p>
    <?php endif; ?>
</body>
</html>
 <!-- #region -->