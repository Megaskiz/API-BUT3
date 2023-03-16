<?php
function connecter_bd() {
    try {
        // Création de la connexion PDO à la base de données
        $conn = new PDO("mysql:host=localhost; dbname=projetapi", 'root', '');

        // Configuration de PDO pour lever des exceptions en cas d'erreur SQL
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $conn; // retourne l'objet de connexion PDO
    }
    catch(PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }
}
