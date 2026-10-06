<?php
require_once __DIR__ . '/../../bdd/bdd.php';
require_once __DIR__ . '/../../controller/PanierController.php';
require_once __DIR__ . '/../../controller/EssaiController.php';

// Vérifier si un ID valide est fourni
$idVoiture = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$idVoiture) {
    echo "<p>Erreur : Aucun véhicule sélectionné.</p>";
    exit();
}

// Récupérer les détails du véhicule
$query = $bdd->prepare("SELECT * FROM voitures WHERE id = :idVoiture");
$query->execute(['idVoiture' => $idVoiture]);
$voiture = $query->fetch(PDO::FETCH_ASSOC);
if (!$voiture) {
    echo "<p>Erreur : Véhicule introuvable.</p>";
    exit();
}

// Vérifier si l'utilisateur est connecté
$utilisateurConnecte = isset($_SESSION['user']);
$idUtilisateur = $utilisateurConnecte ? $_SESSION['user']['ID_Utilisateur'] : null;

// Création des instances des contrôleurs
$panierController = new PanierController($bdd);
$essaiController  = new EssaiController($bdd);

// Traitement de l'ajout au panier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_voiture']) && !isset($_POST['demande_essai'])) {
    if (!$utilisateurConnecte) {
        header('Location: index.php?page=login');
        exit();
    }
    if ($_SESSION['user']['Role'] !== 'Client') {
        echo "Seuls les clients peuvent ajouter des voitures au panier.";
        exit();
    }
    $result = $panierController->ajouterAuPanier($_SESSION['user']['ID_Utilisateur'], $_POST['id_voiture']);
    if (!empty($result['success']) && $result['success']) {
        header('Location: index.php?page=panier');
        exit();
    } else {
        echo "<p>Erreur : " . htmlspecialchars($result['message']) . "</p>";
    }
}

// Récupérer la demande d'essai pour cet utilisateur et ce véhicule depuis la table DemandeEssai
$demandeEssai = null;
if ($utilisateurConnecte && $_SESSION['user']['Role'] === 'Client') {
    $queryEssai = $bdd->prepare("SELECT * FROM DemandeEssai WHERE ID_Utilisateur = :idUtilisateur AND ID_Voiture = :idVoiture");
    $queryEssai->execute([
        'idUtilisateur' => $idUtilisateur,
        'idVoiture'     => $idVoiture
    ]);
    $demandeEssai = $queryEssai->fetch(PDO::FETCH_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du véhicule</title>
    <link rel="stylesheet" href="css/details_voiture.css">
</head>
<body>

<h1>Détails du véhicule</h1>
<div class="voiture-details">
    <img src="<?= htmlspecialchars($voiture['image']) ?>" alt="Image de <?= htmlspecialchars($voiture['marque'] . ' ' . $voiture['modele']) ?>">
    <h2><?= htmlspecialchars($voiture['marque'] . ' ' . $voiture['modele']) ?></h2>
    <p><strong>Année :</strong> <?= htmlspecialchars($voiture['annee']) ?></p>
    <p><strong>Prix :</strong> <?= number_format($voiture['prix'], 2, ',', ' ') ?> €</p>

    <?php if ($utilisateurConnecte && $_SESSION['user']['Role'] === 'Client') : ?>
        <!-- Formulaire d'ajout au panier -->
        <form method="post">
            <input type="hidden" name="id_voiture" value="<?= $idVoiture ?>">
            <button type="submit" class="btn-ajouter">🛒 Ajouter au panier</button>
        </form>
    <?php else : ?>
        <p><a href="index.php?page=login">Connectez-vous</a> pour ajouter au panier.</p>
    <?php endif; ?>

    <!-- Affichage du récapitulatif de la demande d'essai si elle existe -->
    <?php if ($demandeEssai) : ?>
        <h3>Détails de votre demande d'essai :</h3>
        <table>
            <tr>
                <th>Date souhaitée</th>
                <td><?= htmlspecialchars($demandeEssai['date_voulue']) ?></td>
            </tr>
            <tr>
                <th>Fin validité du permis</th>
                <td><?= htmlspecialchars($demandeEssai['fin_validite_permis']) ?></td>
            </tr>
            <tr>
                <th>Année d'obtention du permis</th>
                <td><?= htmlspecialchars($demandeEssai['annee_permis']) ?></td>
            </tr>
            <tr>
                <th>Accompagnant</th>
                <td><?= htmlspecialchars($demandeEssai['accompagnant'] ?: 'Aucun') ?></td>
            </tr>
        </table>
    <?php endif; ?>
</div>

<?php if ($utilisateurConnecte && $_SESSION['user']['Role'] === 'Client'): ?>
    <div class="panier-info">
        <a href="index.php?page=panier">
            <button type="button">Voir le panier</button>
        </a>
    </div>
<?php endif; ?>

</body>
</html>
