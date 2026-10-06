<?php
// Inclure les fichiers nécessaires
include_once dirname(__DIR__, 2) . '/controller/VoitureController.php';
include_once dirname(__DIR__, 2) . '/bdd/bdd.php';

$voitureController = new VoitureController($bdd);

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $marque = isset($_POST['marque']) ? htmlspecialchars($_POST['marque']) : '';
    $modele = isset($_POST['modele']) ? htmlspecialchars($_POST['modele']) : '';
    $annee = isset($_POST['annee']) ? htmlspecialchars($_POST['annee']) : '';
    $prix = isset($_POST['prix']) ? htmlspecialchars($_POST['prix']) : '';
    $image = isset($_FILES['image']) ? $_FILES['image'] : null;

    // Validation des données
    if (empty($marque) || empty($modele) || empty($annee) || empty($prix)) {
        $error_message = "Tous les champs sont obligatoires.";
    } else {
        // Traitement de l'image
        if ($image && $image['error'] === UPLOAD_ERR_OK) {
            // Déplacer l'image téléchargée vers un dossier spécifique
            $target_dir = dirname(__DIR__, 2) . '/images/';
            $target_file = $target_dir . basename($image['name']);
            
            if (move_uploaded_file($image['tmp_name'], $target_file)) {
                // Ajouter le modèle à la base de données
                $voitureController->ajouterVoiture($marque, $modele, $annee, $prix, $image['name']);
                $success_message = "Le modèle a été ajouté avec succès.";
            } else {
                $error_message = "Erreur lors du téléchargement de l'image.";
            }
        } else {
            $error_message = "Veuillez télécharger une image valide.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un modèle</title>
    <link rel="stylesheet" href="../css/ajouter_modeles.css">
</head>
<body>

    <h1>Ajouter un nouveau modèle de voiture</h1>

    <!-- Affichage des messages d'erreur ou de succès -->
    <?php if (isset($error_message)): ?>
        <p style="color: red;"><?= $error_message; ?></p>
    <?php elseif (isset($success_message)): ?>
        <p style="color: green;"><?= $success_message; ?></p>
    <?php endif; ?>

    <!-- Formulaire d'ajout d'un modèle -->
    <form action="index.php?page=ajouter_modeles" method="POST" enctype="multipart/form-data">
        <div>
            <label for="marque">Marque</label>
            <input type="text" id="marque" name="marque" required>
        </div>

        <div>
            <label for="modele">Modèle</label>
            <input type="text" id="modele" name="modele" required>
        </div>

        <div>
            <label for="annee">Année</label>
            <input type="text" id="annee" name="annee" required>
        </div>

        <div>
            <label for="prix">Prix</label>
            <input type="text" id="prix" name="prix" required>
        </div>

        <div>
            <label for="image">Image</label>
            <input type="file" id="image" name="image" accept="image/*" required>
        </div>

        <div>
            <button type="submit">Ajouter le modèle</button>
        </div>
    </form>

    <a href="index.php?page=gerer_modeles">Retour à la gestion des modèles</a>

</body>
</html>
