<?php
session_start();

// Vérification de la session de l'utilisateur (concessionnaire)
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'concessionnaire') {
    header('Location: index.php?page=login'); // Redirection si l'utilisateur n'est pas connecté ou n'est pas concessionnaire
    exit();
}

// Inclusion des fichiers nécessaires
include_once __DIR__ . '/../controller/VoitureController.php';
include_once __DIR__ . '/../bdd/bdd.php';

$voitureController = new VoitureController($bdd);

// Si un formulaire d'ajout de voiture est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'ajouter') {
        $marque = $_POST['marque'];
        $modele = $_POST['modele'];
        $annee = $_POST['annee'];
        $prix = $_POST['prix'];
        $image = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];

        // Vérification que le fichier image est bien téléchargé
        if ($image) {
            move_uploaded_file($tmp_name, 'images/' . $image);
        }

        // Ajout de la voiture dans la base de données
        $voitureController->ajouterVoiture($marque, $modele, $annee, $prix, $image);
        header('Location: index.php?page=concessionnaire'); // Redirection vers la page concessionnaire
        exit();
    }

    // Si un formulaire de modification de voiture est soumis
    if ($_POST['action'] === 'modifier' && isset($_POST['id_voiture'])) {
        $id_voiture = $_POST['id_voiture'];
        $marque = $_POST['marque'];
        $modele = $_POST['modele'];
        $annee = $_POST['annee'];
        $prix = $_POST['prix'];
        $image = $_FILES['image']['name'];
        $tmp_name = $_FILES['image']['tmp_name'];

        // Si une nouvelle image est téléchargée, on l'ajoute
        if ($image) {
            move_uploaded_file($tmp_name, 'images/' . $image);
        } else {
            // Si aucune nouvelle image n'est téléchargée, on conserve l'ancienne
            $image = $_POST['image_existe'];
        }

        // Mise à jour de la voiture dans la base de données
        $voitureController->modifierVoiture($id_voiture, $marque, $modele, $annee, $prix, $image);
        header('Location: index.php?page=concessionnaire'); // Redirection vers la page concessionnaire
        exit();
    }
}

// Si un ID de voiture est spécifié, on veut afficher le formulaire de modification
if (isset($_GET['id'])) {
    $id_voiture = $_GET['id'];
    $voiture = $voitureController->getVoitureById($id_voiture);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Voitures - Concessionnaire</title>
    <link rel="stylesheet" href="css/concessionnaire.css">
</head>
<body>

<h1>Gestion des Voitures - Concessionnaire</h1>

<!-- Formulaire pour ajouter une nouvelle voiture -->
<h2>Ajouter une nouvelle voiture</h2>
<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="action" value="ajouter">
    <label for="marque">Marque :</label>
    <input type="text" name="marque" required><br>

    <label for="modele">Modèle :</label>
    <input type="text" name="modele" required><br>

    <label for="annee">Année :</label>
    <input type="number" name="annee" required><br>

    <label for="prix">Prix (€) :</label>
    <input type="number" name="prix" required><br>

    <label for="image">Image :</label>
    <input type="file" name="image" required><br>

    <button type="submit">Ajouter la voiture</button>
</form>

<!-- Formulaire pour modifier une voiture -->
<?php if (isset($voiture)) : ?>
    <h2>Modifier la voiture</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="modifier">
        <input type="hidden" name="id_voiture" value="<?= $voiture['id'] ?>">
        <input type="hidden" name="image_existe" value="<?= $voiture['image'] ?>">

        <label for="marque">Marque :</label>
        <input type="text" name="marque" value="<?= $voiture['marque'] ?>" required><br>

        <label for="modele">Modèle :</label>
        <input type="text" name="modele" value="<?= $voiture['modele'] ?>" required><br>

        <label for="annee">Année :</label>
        <input type="number" name="annee" value="<?= $voiture['annee'] ?>" required><br>

        <label for="prix">Prix (€) :</label>
        <input type="number" name="prix" value="<?= $voiture['prix'] ?>" required><br>

        <label for="image">Image (optionnel) :</label>
        <input type="file" name="image"><br>
        <img src="images/<?= $voiture['image'] ?>" alt="Image actuelle" width="100"><br>

        <button type="submit">Mettre à jour la voiture</button>
    </form>
<?php endif; ?>

<!-- Liste des voitures -->
<h2>Liste des voitures</h2>
<table>
    <thead>
        <tr>
            <th>Marque</th>
            <th>Modèle</th>
            <th>Année</th>
            <th>Prix</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $voitures = $voitureController->afficherVoitures();
        foreach ($voitures as $voiture) :
        ?>
            <tr>
                <td><?= htmlspecialchars($voiture['marque']) ?></td>
                <td><?= htmlspecialchars($voiture['modele']) ?></td>
                <td><?= $voiture['annee'] ?></td>
                <td><?= number_format($voiture['prix'], 2, ',', ' ') ?> €</td>
                <td>
                    <a href="index.php?page=concessionnaire&id=<?= $voiture['id'] ?>">Modifier</a> |
                    <a href="supprimer_voiture.php?id=<?= $voiture['id'] ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette voiture ?')">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
