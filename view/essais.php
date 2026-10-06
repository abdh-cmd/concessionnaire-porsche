<?php
// Inclusion du contrôleur EssaiController et de la connexion à la base de données
include_once __DIR__ . '/../controller/EssaiController.php';
include_once __DIR__ . '/../bdd/bdd.php';

// Créer une instance du contrôleur pour récupérer les essais
$essaiController = new EssaiController($bdd);

// Vérifier si l'utilisateur est un concessionnaire
$role = isset($_SESSION['user']['Role']) ? $_SESSION['user']['Role'] : null;
if ($role !== 'Concess') {
    header('Location: index.php');
    exit();
}

// Récupérer toutes les demandes d'essai en attente
$essais = $essaiController->getEssaisPourConcess(); // Cette méthode récupère toutes les demandes

// Traitement de l'acceptation ou du refus d'une demande
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idDemande = $_POST['id_demande'];
    $statut = $_POST['statut'];

    if ($statut === 'Acceptée' || $statut === 'Refusée') {
        $result = $essaiController->traiterDemande($idDemande, $statut);
        $message = $result['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des demandes d'essai</title>
    <link rel="stylesheet" href="css/essais.css">
</head>
<body>

<h1>Gestion des demandes d'essai</h1>

<?php if (isset($message)): ?>
    <p class="message"><?= htmlspecialchars($message) ?></p>
<?php endif; ?>

<!-- Table des demandes d'essai -->
<table>
    <thead>
        <tr>
            <th>Nom</th>
            <th>Voiture</th>
            <th>Date de la demande</th>
            <th>Date voulue</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($essais)): ?>
            <tr>
                <td colspan="6">Aucune demande d'essai en attente.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($essais as $essai): ?>
                <tr>
                    <td><?= htmlspecialchars($essai['prenom']) . ' ' . htmlspecialchars($essai['nom']) ?></td>
                    <td><?= htmlspecialchars($essai['marque']) . ' ' . htmlspecialchars($essai['modele']) ?></td>
                    <td><?= htmlspecialchars($essai['date_demande']) ?></td>
                    <td><?= htmlspecialchars($essai['date_voulue']) ?></td>
                    <td>
                        <?php
                        if ($essai['statut'] === 'En attente') {
                            echo '<span class="en-attente">En attente</span>';
                        } elseif ($essai['statut'] === 'Acceptée') {
                            echo '<span class="acceptee">Acceptée</span>';
                        } else {
                            echo '<span class="refusee">Refusée</span>';
                        }
                        ?>
                    </td>
                    <td>
                        <!-- Formulaire pour accepter ou refuser -->
                        <?php if ($essai['statut'] === 'En attente'): ?>
                            <form method="post" action="">
                                <input type="hidden" name="id_demande" value="<?= $essai['ID_Demande'] ?>">
                                <button type="submit" name="statut" value="Acceptée" class="btn-accept">Accepter</button>
                                <button type="submit" name="statut" value="Refusée" class="btn-refuse">Refuser</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

</body>
</html>
