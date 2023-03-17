<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="css/style.css">
    <title>Page de connexion</title>

</head>
<?php
    if(isset($_GET['error'])){
        echo '<center><p class="error">Login ou mot de passe incorrect</p></center>';
    }
?>
<body>
    <h1>Page de connexion</h1>
    <form id="login-form" action="recupToken.php" method="post">
        <input type="text" name="login" placeholder="Nom d'utilisateur" required>
        <input type="password" name="password" placeholder="Mot de passe" required>
        <input type="submit" value="Se connecter">
        <p>Vous n'avez pas de compte ? <a href="clien.php">Inscrivez-vous</a></p>
    </form>
</body>
</html>
