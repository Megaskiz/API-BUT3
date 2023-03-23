<?php
/// Librairies éventuelles (pour la connexion à la BDD, etc.)
include('fonction.php');
include('jwt_utils.php');
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
        if (is_jwt_valid($data['token'])) {
            switch (getRoleFromToken($data['token'])) {

                case "moderator":
                    $name = getLoginFromToken($data['token']);
                    // Récupération des critères de recherche envoyés par le Client
                    if (!empty($_GET['id_article'])) {
                        $id_article = $_GET['id_article'];
                        $result = excuteQuery("SELECT a.id_article, a.titre, u.nom AS auteur, a.date_publication, a.contenu,
                        GROUP_CONCAT(CASE WHEN l.type = 'like' THEN u2.nom ELSE NULL END) AS utilisateurs_likes,
                        COUNT(CASE WHEN l.type = 'like' THEN 1 ELSE NULL END) AS total_likes,
                        GROUP_CONCAT(CASE WHEN l.type = 'dislike' THEN u2.nom ELSE NULL END) AS utilisateurs_dislikes,
                        COUNT(CASE WHEN l.type = 'dislike' THEN 1 ELSE NULL END) AS total_dislikes
                        FROM article a
                        INNER JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur
                        LEFT JOIN liker l ON a.id_article = l.id_article
                        LEFT JOIN utilisateur u2 ON l.id_utilisateur = u2.id_utilisateur
                        WHERE a.id_article = $id_article
                        GROUP BY a.id_article;
                        ");
                        $matchingData  = $result->fetchAll(PDO::FETCH_ASSOC);
                    } else {
                        $result = excuteQuery("SELECT a.id_article, a.titre, u.nom AS auteur, a.date_publication, a.contenu,
                        GROUP_CONCAT(CASE WHEN l.type = 'like' THEN u2.nom ELSE NULL END) AS utilisateurs_likes,
                        COUNT(CASE WHEN l.type = 'like' THEN 1 ELSE NULL END) AS total_likes,
                        GROUP_CONCAT(CASE WHEN l.type = 'dislike' THEN u2.nom ELSE NULL END) AS utilisateurs_dislikes,
                        COUNT(CASE WHEN l.type = 'dislike' THEN 1 ELSE NULL END) AS total_dislikes
                        FROM article a
                        INNER JOIN utilisateur u ON a.id_utilisateur = u.id_utilisateur
                        LEFT JOIN liker l ON a.id_article = l.id_article
                        LEFT JOIN utilisateur u2 ON l.id_utilisateur = u2.id_utilisateur
                        GROUP BY a.id_article;                        
                    ");
                        $matchingData  = $result->fetchAll(PDO::FETCH_ASSOC);
                    }
                    // Envoi de la réponse au Client
                    deliver_response(200, "[GET] Bonjour, $name vous etes moderator", $matchingData);
                    break;

                case "publisher":
                    $name = getLoginFromToken($data['token']);
                    // Récupération des critères de recherche envoyés par le Client
                    if (!empty($_GET['id_article'])) {
                        $id_article = $_GET['id_article'];
                        $result = excuteQuery("SELECT article.id_article, article.titre, article.contenu, article.date_publication, article.id_utilisateur,
                        COUNT(CASE WHEN liker.type = 'like' THEN 1 ELSE NULL END) AS nombre_likes,
                        COUNT(CASE WHEN liker.type = 'dislike' THEN 1 ELSE NULL END) AS nombre_dislikes
                        FROM article
                        LEFT JOIN liker ON article.id_article = liker.id_article
                        GROUP BY article.id_article;");
                        $matchingData  = $result->fetchAll(PDO::FETCH_ASSOC);
                    } else {
                        $result = excuteQuery("SELECT * FROM article ;");
                        $matchingData  = $result->fetchAll(PDO::FETCH_ASSOC);
                    }
                    // Envoi de la réponse au Client
                    deliver_response(200, "[GET] Bonjour, $name vous etes publisher", $matchingData);
                    break;

                default:
                    if (!empty($_GET['id_article'])) {
                        $id_article = $_GET['id_article'];
                        $result = excuteQuery("SELECT titre, contenu FROM article WHERE id_article = $id_article");
                        $matchingData  = $result->fetchAll(PDO::FETCH_ASSOC);
                    } else {
                        $result = excuteQuery("SELECT titre, contenu FROM article;");
                        $matchingData  = $result->fetchAll(PDO::FETCH_ASSOC);
                    }
                    // Envoi de la réponse au Client
                    deliver_response(200, "[GET] Bonjour, vous n'avez pas de role", $matchingData);
                    break;
            }
        } else {
            if (!empty($_GET['id_article'])) {
                $id_article = $_GET['id_article'];
                $result = excuteQuery("SELECT a.titre, a.contenu, u.nom FROM article as a, utilisateur as u WHERE a.id_utilisateur = u.id_utilisateur AND id_article = $id_article");
                $matchingData  = $result->fetchAll(PDO::FETCH_ASSOC);
            } else {
                $result = excuteQuery("SELECT a.titre, a.contenu, u.nom FROM article as a, utilisateur as u WHERE a.id_utilisateur = u.id_utilisateur;");
                $matchingData  = $result->fetchAll(PDO::FETCH_ASSOC);
            }
            // Envoi de la réponse au Client
            deliver_response(200, "[GET] Bonjour, vous n'est pas identifié", $matchingData);
            break;
        }
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
