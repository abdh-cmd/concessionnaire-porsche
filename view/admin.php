<?php
include_once __DIR__ . '/../controller/DemandeEssaiController.php';
include_once __DIR__ . '/../bdd/bdd.php';

$demandeEssaiController = new DemandeEssaiController($bdd);
$demande_essais = $demandeEssaiController->afficherDemandes();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_demande']) && isset($_POST['statut'])) {
    $demandeEssaiController->traiterDemande($_POST['id_demande'], $_POST['statut']);
    header('Location: index.php?page=admin');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gestion des demandes d'essai</title>
    <link rel="stylesheet" href="css/admin.css">
</head>
<body>

<h1>Gestion des demandes d'essai</h1>

<table>
    <thead>
        <tr>
            <th>ID Utilisateur</th>
            <th>Nom</th>
            <th>Voiture demandée</th>
            <th>Date demande</th>
            <th>Statut</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($demande_essais as $demande) : ?>
            <tr>
                <td><?= htmlspecialchars($demande['id_utilisateur']) ?></td>
                <td><?= htmlspecialchars($demande['Nom']) ?> <?= htmlspecialchars($demande['Prenom']) ?></td>
                <td><?= htmlspecialchars($demande['marque'] . ' ' . $demande['modele']) ?></td>
                <td><?= $demande['date_demande'] ?></td>
                <td><?= $demande['statut'] ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="id_demande" value="<?= $demande['id_demande'] ?>">
                        <select name="statut">
                            <option value="Acceptée" <?= $demande['statut'] == 'Acceptée' ? 'selected' : '' ?>>Acceptée</option>
                            <option value="Refusée" <?= $demande['statut'] == 'Refusée' ? 'selected' : '' ?>>Refusée</option>
                        </select>
                        <button type="submit">Mettre à jour</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
