<?php
// Fichier : view/gerer_essais.php
include_once __DIR__ . '/../controller/EssaiController.php';
include_once __DIR__ . '/../bdd/bdd.php';

session_start();

// Vérifier si l'utilisateur est connecté et est bien un concessionnaire
if (!isset($_SESSION['user']) || $_SESSION['user']['Role'] !== 'Concess') {
    echo "<p>Accès refusé. Seuls les concessionnaires peuvent gérer les demandes d'essai.</p>";
    exit();
}

// Initialisation du contrôleur des essais
$essaiController = new EssaiController($bdd);
$message = '';

// Traitement du formulaire de mise à jour du statut d'une demande d'essai
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_demande'], $_POST['statut'])) {
    $idDemande = intval($_POST['id_demande']);
    $statut = $_POST['statut'];

    if (!in_array($statut, ['Acceptée', 'Refusée'])) {
        $message = "Statut invalide.";
    } else {
        $result = $essaiController->traiterDemande($idDemande, $statut);
        $message = $result['message'];
    }
}

// Récupérer toutes les demandes d'essai pour le concessionnaire (depuis la table demandeessai)
$essais = $essaiController->getEssaisPourConcess();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Demandes d'Essai</title>
    <link rel="stylesheet" href="css/admin.css">
    <style>
        /* Utilisation de classes similaires à celles de la page stats */
        .stat-form {
            width: 90%;
            margin: 20px auto;
            padding: 15px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .stat-row {
            display: flex;
            flex-wrap: wrap;
            margin-bottom: 10px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .stat-row label {
            width: 150px;
            font-weight: bold;
        }
        .stat-row input {
            width: 200px;
            margin-right: 10px;
            border: none;
            background-color: #f9f9f9;
            padding: 5px;
        }
        .stat-row select {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        .stat-form button {
            padding: 6px 12px;
            background-color: #375dc5;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
        .stat-form button:hover {
            background-color: #AF1740;
        }
        .message {
            text-align: center;
            color: #AF1740;
            font-weight: bold;
            margin: 10px;
        }
    </style>
</head>
<body>
    <h1>Gestion des Demandes d'Essai</h1>
    <?php if (!empty($message)): ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if (empty($essais)): ?>
        <p>Aucune demande d'essai enregistrée.</p>
    <?php else: ?>
        <?php foreach ($essais as $essai): ?>
            <form method="POST" class="stat-form">
                <h2>Demande ID : <?= htmlspecialchars($essai['ID_Demande']) ?></h2>
                <div class="stat-row">
                    <label>Client :</label>
                    <input type="text" value="<?= htmlspecialchars($essai['nom'] . ' ' . $essai['prenom']) ?>" readonly>
                </div>
                <div class="stat-row">
                    <label>Voiture :</label>
                    <input type="text" value="<?= htmlspecialchars($essai['marque'] . ' ' . $essai['modele']) ?>" readonly>
                </div>
                <div class="stat-row">
                    <label>Date de la demande :</label>
                    <input type="text" value="<?= htmlspecialchars($essai['date_demande']) ?>" readonly>
                </div>
                <div class="stat-row">
                    <label>Date voulue :</label>
                    <input type="text" value="<?= htmlspecialchars($essai['date_voulue']) ?>" readonly>
                </div>
                <div class="stat-row">
                    <label>Statut :</label>
                    <select name="statut" required>
                        <option value="Acceptée" <?= ($essai['statut'] === 'Acceptée') ? 'selected' : '' ?>>Accepter</option>
                        <option value="Refusée" <?= ($essai['statut'] === 'Refusée') ? 'selected' : '' ?>>Refuser</option>
                    </select>
                </div>
                <input type="hidden" name="id_demande" value="<?= intval($essai['ID_Demande']) ?>">
                <div class="stat-row">
                    <button type="submit">Mettre à jour</button>
                </div>
            </form>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
