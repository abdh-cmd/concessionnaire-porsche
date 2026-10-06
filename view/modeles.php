<?php
include_once __DIR__ . '/../controller/VoitureController.php';
include_once __DIR__ . '/../controller/PanierController.php';
include_once __DIR__ . '/../controller/EssaiController.php';
include_once __DIR__ . '/../bdd/bdd.php';

$voitureController = new VoitureController($bdd);
$panierController  = new PanierController($bdd);
$essaiController   = new EssaiController($bdd);

$voitures = $voitureController->afficherVoitures();

// Traitement de l'ajout au panier
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'ajouter_panier') {
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?page=login');
        exit();
    }
    if ($_SESSION['user']['Role'] !== 'Client') {
        echo "Seuls les clients peuvent ajouter des voitures au panier.";
        exit();
    }
    $result = $panierController->ajouterAuPanier($_SESSION['user']['ID_Utilisateur'], $_POST['id_voiture']);
    if ($result['status']) {
        header('Location: index.php?page=panier');
        exit();
    } else {
        echo $result['message'];
    }
}

// Récupération du contenu du panier (optionnel)
$panier = [];
if (isset($_SESSION['user']) && $_SESSION['user']['Role'] === 'Client') {
    $panier = $panierController->afficherPanier($_SESSION['user']['ID_Utilisateur']);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modèles Porsche</title>
    <link rel="stylesheet" href="css/modeles.css">
    <script>
        function toggleEssaiForm(id) {
            var form = document.getElementById('essai-form-' + id);
            if (form.style.display === 'none' || form.style.display === '') {
                form.style.display = 'block';
            } else {
                form.style.display = 'none';
            }
        }
    </script>
</head>
<body>

<h1>Modèles de voitures Porsche</h1>
<div class="container">
    <?php foreach ($voitures as $voiture) : ?>
        <div class="car-card">
            <img src="images/<?= htmlspecialchars($voiture['image']) ?>" alt="<?= htmlspecialchars($voiture['marque'] . ' ' . $voiture['modele']) ?>">
            <h2><?= htmlspecialchars($voiture['marque'] . ' ' . $voiture['modele']) ?></h2>
            <p>Année : <?= htmlspecialchars($voiture['annee']) ?></p>
            <p>Prix : <?= number_format($voiture['prix'], 2, ',', ' ') ?> €</p>
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['Role'] === 'Client') : ?>
                <!-- Formulaire d'ajout au panier -->
                <form method="post">
                    <input type="hidden" name="id_voiture" value="<?= htmlspecialchars($voiture['id']) ?>">
                    <input type="hidden" name="action" value="ajouter_panier">
                    <button type="submit">Ajouter au panier</button>
                </form>
                <!-- Boutons pour demander un essai et voir les détails -->
                <div class="buttons">
                    <button type="button" onclick="toggleEssaiForm(<?= htmlspecialchars($voiture['id']) ?>)">Demander un essai</button>
                    <a href="index.php?page=details_voiture&id=<?= htmlspecialchars($voiture['id']) ?>">
                        <button type="button">Voir détails</button>
                    </a>
                </div>
                <!-- Formulaire de demande d'essai (caché par défaut) avec les nouveaux champs -->
                <div id="essai-form-<?= htmlspecialchars($voiture['id']) ?>" style="display: none; margin-top: 10px;">
                    <form method="post" action="index.php?page=demande_essai">
                        <input type="hidden" name="id_voiture" value="<?= htmlspecialchars($voiture['id']) ?>">
                        
                        <label for="date_voulue-<?= htmlspecialchars($voiture['id']) ?>">Date souhaitée :</label>
                        <input type="date" id="date_voulue-<?= htmlspecialchars($voiture['id']) ?>" name="date_voulue" required>
                        
                        <label for="fin_validite-<?= htmlspecialchars($voiture['id']) ?>">Fin validité du permis :</label>
                        <input type="date" id="fin_validite-<?= htmlspecialchars($voiture['id']) ?>" name="fin_validite_permis" required>
                        
                        <label for="annee_permis-<?= htmlspecialchars($voiture['id']) ?>">Année d'obtention du permis :</label>
                        <input type="number" id="annee_permis-<?= htmlspecialchars($voiture['id']) ?>" name="annee_permis" min="1900" max="<?= date('Y') ?>" required>
                        
                        <label for="accompagnant-<?= htmlspecialchars($voiture['id']) ?>">Accompagnant (optionnel) :</label>
                        <input type="text" id="accompagnant-<?= htmlspecialchars($voiture['id']) ?>" name="accompagnant">
                        
                        <button type="submit">Confirmer la demande</button>
                        <button type="button" onclick="toggleEssaiForm(<?= htmlspecialchars($voiture['id']) ?>)">Annuler</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
<?php if (isset($_SESSION['user']) && $_SESSION['user']['Role'] === 'Client') : ?>
    <div class="panier-info">
        <a href="index.php?page=panier">
            <button type="button">Voir le panier (<?= count($panier['data'] ?? []) ?>)</button>
        </a>
    </div>
<?php endif; ?>
</body>
</html>
