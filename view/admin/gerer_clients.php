<?php
// Démarrer la session uniquement si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inclusion de la connexion à la base de données
include_once __DIR__ . '/../../bdd/bdd.php';

// Vérifier si l'utilisateur est connecté et s'il est un concessionnaire
if (!isset($_SESSION['user']) || $_SESSION['user']['Role'] !== 'Concess') {
    echo "<p>Accès refusé. Seuls les concessionnaires peuvent accéder à cette page.</p>";
    exit();
}

// Gestion de la suppression d'un client
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $idToDelete = intval($_POST['delete_id']);

    $stmt = $bdd->prepare("DELETE FROM Utilisateurs WHERE ID_Utilisateur = :id AND Role = 'Client'");
    $stmt->bindParam(':id', $idToDelete, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        $message = "Le client a bien été supprimé.";
    } else {
        $message = "Erreur lors de la suppression du client.";
    }
}

// Récupération des clients depuis la base de données
$query = $bdd->query("SELECT ID_Utilisateur, Nom, Prenom, Email FROM Utilisateurs WHERE Role = 'Client'");
$clients = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Clients</title>
    <link rel="stylesheet" href="css/admin.css">
    <script>
        function confirmDelete(clientId) {
            if (confirm("Voulez-vous vraiment supprimer ce client ?")) {
                document.getElementById('delete-form-' + clientId).submit();
            }
        }
    </script>
</head>
<body>

    <h1>Gestion des Clients</h1>

    <?php if (isset($message)) : ?>
        <p class="message"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <?php if (empty($clients)) : ?>
        <p>Aucun client enregistré dans la base de données.</p>
    <?php else : ?>
        <table class="table-clients">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client) : ?>
                    <tr>
                        <td><?= htmlspecialchars($client['ID_Utilisateur']) ?></td>
                        <td><?= htmlspecialchars($client['Nom']) ?></td>
                        <td><?= htmlspecialchars($client['Prenom']) ?></td>
                        <td><?= htmlspecialchars($client['Email']) ?></td>
                        <td>
                            <a href="index.php?page=modifier_client&id=<?= $client['ID_Utilisateur'] ?>" class="btn-modifier">Modifier</a>
                            <form id="delete-form-<?= $client['ID_Utilisateur'] ?>" action="" method="POST" style="display:inline;">
                                <input type="hidden" name="delete_id" value="<?= $client['ID_Utilisateur'] ?>">
                                <button type="button" class="btn-supprimer" onclick="confirmDelete(<?= $client['ID_Utilisateur'] ?>)">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>

</body>
</html>
