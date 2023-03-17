<?php
function connecter_bd()
{
    try {
        // Création de la connexion PDO à la base de données
        $conn = new PDO("mysql:host=localhost; dbname=projetapi", "root", "");
        return $conn;
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }
}
