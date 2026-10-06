<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Luxury Cars</title>
    <link rel="stylesheet" href="css/header.css"> <!-- Assurez-vous que ce lien vers header.css est correct -->
    <style>
        /* Style global */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center; /* Centrage horizontal */
            align-items: center; /* Centrage vertical */
            height: 100vh; /* Utilise toute la hauteur de la page */
        }

        /* Style pour le header fixe en haut */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 20px;
            background-color: #1f1f1f;
            z-index: 1000;
        }

        nav .logo {
            font-size: 24px;
            color: #fff;
            text-decoration: none;
            font-weight: bold;
        }

        .nav-links {
            display: flex;
            list-style: none;
            margin-left: auto;
        }

        .nav-links li {
            margin-right: 20px;
        }

        .nav-links a {
            text-decoration: none;
            color: #fff;
            font-size: 16px;
        }

        .nav-links a:hover {
            color: #AF1740;
        }

        /* Style pour la page de connexion */
        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 300px;
            box-sizing: border-box;
            margin-top: 100px;  /* Pour que le formulaire soit sous le header fixe */
        }

        h1 {
            text-align: center;
            color: #AF1740;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #AF1740;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #9a1536;
        }

        p {
            text-align: center;
            margin-top: 15px;
        }

        p a {
            color: #AF1740;
            text-decoration: none;
        }

        p a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Login</h1>

        <form action="controller/utilisateurController.php" method="POST">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required><br>

            <label for="password">Mot de passe</label>
            <input type="password" id="password" name="password" required><br>

            <input type="hidden" name="action" value="login"><br>
            <input type="submit" value="Se connecter"><br>
        </form>

        <p>Pas encore de compte ? <a href="index.php?page=inscription">Créer un compte ici</a></p>
    </div>
</body>
</html>
