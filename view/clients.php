<?php
require_once __DIR__ . '/../bdd/bdd.php';
session_start();

// Vérification du rôle de l'utilisateur (seul un concessionnaire peut voir cette page)
if (!isset($_SESSION['user']) || $_SESSION['user']['Role'] !== 'Concess') {
    header('Location: index.php');
    exit();
}

// Récupérer tous les clients triés par ID
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
    <title>Liste des Clients</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
        }
        .btn {
            padding: 8px 12px;
            text-decoration: none;
            border: none;
            cursor: pointer;
        }
        .btn-danger {
            background-color: red;
            color: white;
        }
        .btn-back {
            background-color: gray;
            color: white;
            display: inline-block;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

<h1>Liste des Clients</h1>

<a href="menu.php" class="btn btn-back">Retour au menu</a>

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
                <th>Action</th>
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
                        <form action="supprimer_client.php" method="post" onsubmit="return confirm('Voulez-vous vraiment supprimer ce client ?');">
                            <input type="hidden" name="id_client" value="<?= htmlspecialchars($client['ID_Utilisateur']) ?>">
                            <button type="submit" class="btn btn-danger">Supprimer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

</body>
</html>
