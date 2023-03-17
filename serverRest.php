<?php
/// Librairies éventuelles (pour la connexion à la BDD, etc.)
include('fonction.php');

/// Paramétrage de l'entête HTTP (pour la réponse au Client)
header("Content-Type:application/json");
/// Identification du type de méthode HTTP envoyée par le client
$http_method = $_SERVER['REQUEST_METHOD'];
switch ($http_method) {

        /// Cas de la méthode GET
        case "GET":
            // Récupération du body
            $postedData = file_get_contents('php://input');
            $data = json_decode($postedData, true);
            // Récupération des critères de recherche envoyés par le Client
            if (!empty($_GET['id_article'])) {
                $id_article = $_GET['id_article'];
                $result = excuteQuery("SELECT * FROM `article` WHERE id_article = $id_article");
                $matchingData  = $result->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $result = excuteQuery("SELECT * FROM `article`");
                $matchingData  = $result->fetchAll(PDO::FETCH_ASSOC);
            }
            // Envoi de la réponse au Client
            deliver_response(200, "Voici les articles" , $matchingData);
            break;
        

        /// Cas de la méthode POST
    case "POST":
        /// Récupération des données envoyées par le Client
        $postedData = file_get_contents('php://input');
        /// Traitement
        /// Envoi de la réponse au Client
        deliver_response(201, "Votre message", NULL);
        break;

        /// Cas de la méthode PUT
    case "PUT":
        /// Récupération des données envoyées par le Client
        $postedData = file_get_contents('php://input');
        /// Traitement
        /// Envoi de la réponse au Client
        deliver_response(200, "Votre message", NULL);
        break;

        /// Cas de la méthode DELETE
    default:
        /// Récupération de l'identifiant de la ressource envoyé par le Client
        if (!empty($_GET['mon_id'])) {
            /// Traitement
        }
        /// Envoi de la réponse au Client
        deliver_response(200, "Votre message", NULL);
        break;
}
/// Envoi de la réponse au Client
