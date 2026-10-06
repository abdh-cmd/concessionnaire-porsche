<?php
include_once('controller/VoitureController.php');
require_once('bdd/bdd.php');

// Vérifier si l'utilisateur est un concessionnaire
if (!isset($_SESSION['user']) || $_SESSION['user']['Role'] !== 'Concess') {
    echo "<p>Accès refusé. Seuls les concessionnaires peuvent modifier les modèles.</p>";
    exit();
}

$voitureController = new VoitureController($bdd);

// Vérification de l'ID dans l'URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "ID invalide!";
    exit();
}

$id = intval($_GET['id']);
$voiture = $voitureController->getVoitureById($id);

// Vérifier si la voiture existe
if (!$voiture) {
    echo "Voiture non trouvée!";
    exit();
}

// Traitement du formulaire de modification
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $annee = $_POST['annee'];
    $prix = $_POST['prix'];

    // Gestion de l'image
    $image = $voiture['image']; // Conserver l'image actuelle par défaut
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $imagePath = 'images/' . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            $image = $_FILES['image']['name'];
        } else {
            echo "Erreur lors de l'upload de l'image.";
        }
    }

    // Modifier la voiture
    $result = $voitureController->modifierVoiture($id, $marque, $modele, $annee, $prix, $image);

    if (isset($result['error'])) {
        echo $result['error'];
    } else {
        header('Location: index.php?page=gerer_modeles');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le modèle</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<h1>Modifier le modèle</h1>

<form method="POST" enctype="multipart/form-data">
    <label for="marque">Marque :</label>
    <input type="text" name="marque" value="<?= htmlspecialchars($voiture['marque']) ?>" required>

    <label for="modele">Modèle :</label>
    <input type="text" name="modele" value="<?= htmlspecialchars($voiture['modele']) ?>" required>

    <label for="annee">Année :</label>
    <input type="number" name="annee" value="<?= htmlspecialchars($voiture['annee']) ?>" required>

    <label for="prix">Prix :</label>
    <input type="text" name="prix" value="<?= htmlspecialchars($voiture['prix']) ?>" required>

    <label for="image">Image :</label>
    <input type="file" name="image">
    <p>Image actuelle : <img src="images/<?= htmlspecialchars($voiture['image']) ?>" width="100"></p>

    <button type="submit" class="btn btn-primary">Modifier</button>
</form>

</body>
</html>
