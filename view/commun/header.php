<?php
$role = isset($_SESSION['user']['Role']) ? $_SESSION['user']['Role'] : null;
$demandeEssai = isset($_SESSION['user']['demande_essai']) ? $_SESSION['user']['demande_essai'] : false;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Porsche</title>
    <link rel="stylesheet" href="css/header.css">
</head>
<body>
    <nav>
        <a href="index.php" class="logo">Porsche</a>
        <ul class="nav-links">
            <?php if (!isset($_SESSION['user'])): ?>
                <!-- Si l'utilisateur n'est pas connecté -->
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=login">
                        <button class="btn-logout">Connexion</button>
                    </a>
                </li>
            <?php else: ?>
                <!-- Si l'utilisateur est connecté -->
                <?php if ($role === 'Client'): ?>
                    <li><a href="index.php?page=accueil">Accueil</a></li>
                    <li><a href="index.php?page=modeles">Modèles</a></li>
                    <?php if ($demandeEssai): ?>
                        <li><a href="index.php?page=essai">Essai</a></li>
                    <?php endif; ?>
                    <li><a href="index.php?page=panier">Panier</a></li>
                    <li><a href="index.php?page=profil">Profil</a></li>
                <?php elseif ($role === 'Concess'): ?>
                    <li><a href="index.php?page=gerer_modeles">Gérer modèles</a></li>
                    <li><a href="index.php?page=ajouter_modeles">Ajouter voiture</a></li>
                    <li><a href="index.php?page=page_essais">Essais</a></li>
                    <li><a href="index.php?page=gerer_clients">Gérer clients</a></li>
                    <li><a href="index.php?page=stats">Stats</a></li>
                <?php endif; ?>
                <!-- Bouton de déconnexion -->
                <li class="nav-item">
                    <a class="nav-link" href="index.php?page=deconnexion">
                        <button class="btn-logout">Déconnexion</button>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</body>
</html>
