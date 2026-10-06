<?php
require_once __DIR__ . '/../bdd/bdd.php';
require_once __DIR__ . '/../controller/PanierController.php';

// Vérification de la connexion de l'utilisateur
if (!isset($_SESSION['user'])) {
    header('Location: index.php?page=login');
    exit();
}

$panierController = new PanierController($bdd);
$idUtilisateur = $_SESSION['user']['ID_Utilisateur'];

// Gestion des actions (ajout, suppression ou vider le panier)
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$idVoitureAction = filter_input(INPUT_GET, 'id_voiture', FILTER_VALIDATE_INT);

// Ajouter une voiture au panier
if ($action === 'ajouter' && $idVoitureAction) {
    $panierController->ajouterAuPanier($idUtilisateur, $idVoitureAction);
    header('Location: index.php?page=panier'); // Recharge la page après ajout
    exit();
}

// Supprimer une voiture du panier
if ($action === 'supprimer' && $idVoitureAction) {
    $panierController->supprimerVoitureDuPanier($idUtilisateur, $idVoitureAction);
    header('Location: index.php?page=panier'); // Recharge la page après suppression
    exit();
}

// Vider tout le panier
if ($action === 'vider') {
    $panierController->viderPanierUtilisateur($idUtilisateur);
    header('Location: index.php?page=panier'); // Recharge la page après vidage
    exit();
}

// Récupération des articles du panier
$panier = $panierController->afficherPanier($idUtilisateur);
$total = 0;
?>

<h1>Mon Panier</h1>

<?php if (empty($panier['data'])) : ?>
    <p>Votre panier est vide.</p>
<?php else : ?>
    <div class="panier-container">
        <?php foreach ($panier['data'] as $item) : ?>
            <?php 
                $quantite = !empty($item['quantite']) ? $item['quantite'] : 1;
                $totalArticle = $item['prix'] * $quantite;
                $total += $totalArticle;
            ?>
            <div class="panier-item">
                <img src="images/<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['marque'] . ' ' . $item['modele']) ?>" class="panier-item-img">
                <h2><?= htmlspecialchars($item['marque'] . ' ' . $item['modele']) ?></h2>
                <p><strong>Prix unitaire :</strong> <?= number_format($item['prix'], 2, ',', ' ') ?> €</p>
                <p><strong>Quantité :</strong> <?= $quantite ?></p>
                <p><strong>Total :</strong> <?= number_format($totalArticle, 2, ',', ' ') ?> €</p>

                <!-- Bouton de suppression avec confirmation -->
                <a href="index.php?page=panier&action=supprimer&id_voiture=<?= $item['id'] ?>"
                   onclick="return confirm('Voulez-vous vraiment supprimer cet article du panier ?');"
                   class="btn-supprimer">
                   ❌ Supprimer
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <h3>Total du panier : <?= number_format($total, 2, ',', ' ') ?> €</h3>

    <!-- Bouton pour vider le panier -->
    <div class="panier-actions">
        <a href="index.php?page=panier&action=vider" 
           onclick="return confirm('Êtes-vous sûr de vouloir vider tout le panier ?');"
           class="btn-vider-panier">Vider le panier</a>
    </div>
<?php endif; ?>
