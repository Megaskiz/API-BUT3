<?php
function connecter_bd()
{
    $servername = "localhost"; // nom du serveur MySQL (ici, le serveur est en local)
    $username = "root"; // nom d'utilisateur MySQL
    $dbname = "projetapi"; // nom de la base de données MySQL

    // Création de la connexion MySQL
    $conn = new mysqli($servername, $username, '', $dbname);

    // Vérification de la connexion
    if ($conn->connect_error) {
        die("Erreur de connexion à la base de données : " . $conn->connect_error);
    }

    return $conn; // retourne l'objet de connexion MySQL
}
