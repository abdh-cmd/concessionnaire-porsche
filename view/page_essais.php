<?php
// Inclusion du contrôleur EssaiController et de la connexion à la base de données
include_once __DIR__ . '/../controller/EssaiController.php';
include_once __DIR__ . '/../bdd/bdd.php';

// Créer une instance du contrôleur pour récupérer les demandes d'essai
$essaiController = new EssaiController($bdd);

// Vérifier si l'utilisateur est un concessionnaire
$role = isset($_SESSION['user']['Role']) ? $_SESSION['user']['Role'] : null;
if ($role !== 'Concess') {
    header('Location: index.php');
    exit();
}

// Traitement de l'acceptation ou du refus d'une demande
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['id_demande'], $_POST['statut'])) {
        $idDemande = filter_var($_POST['id_demande'], FILTER_VALIDATE_INT);
        $statut = $_POST['statut'];


        
        if ($idDemande && in_array($statut, ['Acceptee', 'Refusee'])) {
            // Appel au contrôleur pour traiter la demande d'essai
            $result = $essaiController->traiterDemande($idDemande, $statut);

            // Vérification du résultat et message approprié
            if ($result['success']) {
                $_SESSION['message'] = "Statut mis à jour avec succès.";
            } else {
                $_SESSION['message'] = "Erreur lors de la mise à jour du statut.";
            }
        } else {
            $_SESSION['message'] = "Erreur : Données invalides.";
        }
    }
}

// Récupérer toutes les demandes d'essai en attente ou celles traitées
$essais = $essaiController->getEssaisPourConcess(); 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les demandes d'essai</title>
    <link rel="stylesheet" href="css/essais.css">
</head>
<body>

<h1>Gestion des demandes d'essai</h1>

<!-- Affichage du message flash -->
<?php if (isset($_SESSION['message'])): ?>
    <p class="message"><?= htmlspecialchars($_SESSION['message']) ?></p>
    <?php unset($_SESSION['message']); // Supprime le message après affichage ?>
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
                        } elseif ($essai['statut'] === 'Acceptee') {
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
                                <input type="hidden" name="id_demande" value="<?= htmlspecialchars($essai['ID_Demande']) ?>">
                                <button type="submit" name="statut" value="Acceptee" class="btn-accept">Accepter</button>
                                <button type="submit" name="statut" value="Refusee" class="btn-refuse">Refuser</button>
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
