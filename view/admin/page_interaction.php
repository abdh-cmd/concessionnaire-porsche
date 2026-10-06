<?php
require_once __DIR__ . '/../bdd/bdd.php';

// Vérification du rôle (seul un concessionnaire peut voir cette page)
session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['Role'] !== 'Concess') {
    header('Location: index.php');
    exit();
}

// Récupérer la liste des clients
$query = $bdd->prepare("
    SELECT ID_Utilisateur, nom, prenom, email, telephone 
    FROM utilisateurs 
    WHERE Role = 'Client'
    ORDER BY ID_Utilisateur ASC
");
$query->execute();
$clients = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interaction avec les Clients</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>

<h1>Interactions avec les Clients</h1>

<?php if (empty($clients)) : ?>
    <p>Aucun client enregistré.</p>
<?php else : ?>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clients as $client) : ?>
                <tr>
                    <td><?= htmlspecialchars($client['ID_Utilisateur']) ?></td>
                    <td><?= htmlspecialchars($client['nom']) ?></td>
                    <td><?= htmlspecialchars($client['prenom']) ?></td>
                    <td><?= htmlspecialchars($client['email']) ?></td>
                    <td><?= htmlspecialchars($client['telephone']) ?></td>
                    <td>
                        <!-- Formulaire pour envoyer un message -->
                        <form action="envoyer_message.php" method="post">
                            <input type="hidden" name="id_client" value="<?= $client['ID_Utilisateur'] ?>">
                            <input type="text" name="message" placeholder="Envoyer un message..." required>
                            <button type="submit">Envoyer</button>
                        </form>

                        <!-- Formulaire pour gérer une demande d’essai -->
                        <form action="gerer_demande_essai.php" method="post">
                            <input type="hidden" name="id_client" value="<?= $client['ID_Utilisateur'] ?>">
                            <select name="statut">
                                <option value="Approuvé">Approuver</option>
                                <option value="Refusé">Refuser</option>
                            </select>
                            <button type="submit">Mettre à jour</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

</body>
</html>
