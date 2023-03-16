<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page de connexion</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f8f8f8;
        }

        h1 {
            text-align: center;
            font-size: 2em;
            margin-top: 50px;
        }

        #login-form {
            width: 300px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            padding: 20px;
            box-sizing: border-box;
            margin-top: 30px;
            position: relative;
        }

        #login-form::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.5);
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        #login-form:hover::before {
            opacity: 1;
        }

        #login-form input {
            width: 100%;
            padding: 10px;
            border: none;
            border-bottom: 1px solid #ccc;
            margin-bottom: 20px;
            box-sizing: border-box;
            font-size: 1em;
        }

        #login-form input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            cursor: pointer;
        }

        #login-form input[type="submit"]:hover {
            background-color: #3e8e41;
        }

        #login-form p {
            font-size: 0.8em;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Page de connexion</h1>
    <form id="login-form" action="authentification.php" method="post">
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <input type="submit" value="Se connecter">
        <p>Vous n'avez pas de compte ? <a href="inscription.php">Inscrivez-vous</a></p>
    </form>
    <p>Continuer sans se connecter</p>
</body>
</html>
