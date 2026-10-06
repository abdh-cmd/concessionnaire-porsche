<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once dirname(__DIR__, 2) . '/controller/VoitureController.php';
include_once dirname(__DIR__, 2) . '/bdd/bdd.php';

$voitureController = new VoitureController($bdd);

// Vérifier si un ID est passé dans l'URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<p>Erreur : Aucun véhicule sélectionné.</p>";
    exit();
}

$id = intval($_GET['id']); // Sécurisation de l'ID

// Récupération des détails du modèle
$voiture = $voitureController->getVoitureById($id);

// Vérifier si la voiture a bien été trouvée
if (!$voiture) {
    echo "<p>Véhicule introuvable.</p>";
    exit();
}

// Extraction des informations de la voiture
$marque = htmlspecialchars($voiture['marque']);
$modele = htmlspecialchars($voiture['modele']);
$annee = htmlspecialchars($voiture['annee']);
$prix = number_format($voiture['prix'], 2, ',', ' ') . ' €';
$image = htmlspecialchars($voiture['image']) ? $voiture['image'] : 'default.jpg'; // Image par défaut si aucune image n'est fournie

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du modèle <?= $marque . ' ' . $modele ?></title>
    <link rel="stylesheet" href="css/details.css">
</head>
<body>

<h1>Détails du modèle <?= $marque . ' ' . $modele ?></h1>

<!-- Affichage de l'image de la voiture -->
<img src="images/<?= $image ?>" alt="<?= $marque . ' ' . $modele ?>">

<p><strong>Marque :</strong> <?= $marque ?></p>
<p><strong>Modèle :</strong> <?= $modele ?></p>
<p><strong>Année :</strong> <?= $annee ?></p>
<p><strong>Prix :</strong> <?= $prix ?></p>

<!-- Optionnel : Boutons d'action, par exemple, ajouter au panier ou demander un essai -->
<?php if (isset($_SESSION['user']) && $_SESSION['user']['Role'] === 'Client') : ?>
    <form method="post">
        <input type="hidden" name="id_voiture" value="<?= $id ?>">
        <button type="submit">Ajouter au panier</button>
    </form>
    <a href="index.php?page=demande_essai&id=<?= $id ?>">
        <button type="button">Demander un essai</button>
    </a>
<?php endif; ?>

<!-- Lien pour revenir à la liste des modèles -->
<a href="index.php?page=modeles">Retour aux modèles</a>

</body>
</html>
