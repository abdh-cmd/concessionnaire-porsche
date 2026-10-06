<?php
include_once('controller/VoitureController.php');

// Vérifier si l'utilisateur est un concessionnaire
if (!isset($_SESSION['user']) || $_SESSION['user']['Role'] !== 'Concess') {
    header("Location: index.php");
    exit();
}

$voitureController = new VoitureController($bdd);

// Récupérer tous les modèles de voitures
$modeles = $voitureController->afficherVoitures();

// Gérer les messages de succès ou d'erreur
$message = '';
$messageType = '';

if (isset($_GET['success'])) {
    $message = "Le modèle a été supprimé avec succès.";
    $messageType = 'success';
} elseif (isset($_GET['error'])) {
    $message = "Erreur : " . htmlspecialchars($_GET['error']);
    $messageType = 'error';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les modèles</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h1>Gérer les modèles</h1>

<?php if ($message): ?>
    <div class="alert alert-<?= $messageType ?>">
        <?= $message ?>
    </div>
<?php endif; ?>

<?php if (empty($modeles)) : ?>
    <p>Aucun modèle disponible.</p>
<?php else : ?>
    <table border="1">
        <tr>
            <th>Image</th>
            <th>Marque</th>
            <th>Modèle</th>
            <th>Année</th>
            <th>Prix</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($modeles as $modele) : ?>
            <tr>
                <td><img src="images/<?= htmlspecialchars($modele['image']) ?>" alt="<?= htmlspecialchars($modele['marque']) . ' ' . htmlspecialchars($modele['modele']) ?>" width="100"></td>
                <td><?= htmlspecialchars($modele['marque']) ?></td>
                <td><?= htmlspecialchars($modele['modele']) ?></td>
                <td><?= htmlspecialchars($modele['annee']) ?></td>
                <td><?= number_format($modele['prix'], 2, ',', ' ') ?> €</td>
                <td>
                    <a href="index.php?page=modifier_modele&id=<?= $modele['id'] ?>" class="btn btn-primary">Modifier</a>
                    <a href="index.php?page=supprimer_modele&id=<?= $modele['id'] ?>" 
                       class="btn btn-danger"
                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce modèle ? Cette action supprimera également toutes les entrées associées dans le panier et les demandes d\'essai.');">
                        Supprimer
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

</body>
</html>