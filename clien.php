<!DOCTYPE html>
<html lang="fr">

<head>
    <title>page d'aceuil</title>
</head>

<?php
session_start();
include('fonction.php');
include('jwt_utils.php');
?>

<body>
    <header>
        <h1>Page d'acceuil</h1>
            <div>
                <?php
                if (isset($_SESSION['token'])) {
                    //afficher le nom d'utilisateur
                    echo getLoginFromToken($_SESSION['token']);
                    echo '<br>';
                    echo getRoleFromToken($_SESSION['token']);
                } else {
                    echo 'Vous n\'êtes pas connecté';
                }
                ?>
            </div>
        <div>
            <?php
            if (isset($_SESSION['token'])) {
                session_destroy();
                echo '<a href="page_connection.php">Se déconnecter</a>';
            } else {
                echo '<a href="page_connection.php">Se connecter</a>';
            }
            ?>
        </div>
    </header>
    <div>
        <?php
        ////////////////// Cas des méthodes GET //////////////////
        $data = file_get_contents(
            'http://localhost/projet_api/serveurAuthentication.php',
            false,
            stream_context_create(array('http' => array('method' => 'GET'))) // ou DELETE
        );

        $json = json_decode($data, true);
        ?>
        <h2>Les articles</h2>
        <ul>
            <?php
            foreach ($json['data'] as $article) {
                echo '<li>' . $article['titre'] . '</li>';
            }
            ?>
        </ul>
    </div>
</body>
</html>