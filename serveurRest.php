<?php
/// Librairies éventuelles (pour la connexion à la BDD, etc.)
include('fonction.php');
include('jwt_utils.php');
/// Paramétrage de l'entête HTTP (pour la réponse au Client)
header("Content-Type:application/json");
/// Identification du type de méthode HTTP envoyée par le client
$secret = "HereistherestapiofadamandjonathaninphpfortheresourceR4.01";

$http_method = $_SERVER['REQUEST_METHOD'];

/// Récupération du body envoyé par le Client
$postedData = file_get_contents('php://input');
$data = json_decode($postedData, true);

switch ($http_method) {

    case "GET":
        if (is_jwt_valid($data['token'], $secret)) {
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
                        GROUP_CONCAT(CASE WHEN l.type = 'like' THEN u2.nom ELSE NULL END) AS liste_utilisateurs_likes,
                        COUNT(CASE WHEN l.type = 'like' THEN 1 ELSE NULL END) AS total_likes,
                        GROUP_CONCAT(CASE WHEN l.type = 'dislike' THEN u2.nom ELSE NULL END) AS liste_utilisateurs_dislikes,
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
                    deliver_response(200, "[GET] Bonjour, $name vous êtes moderator", $matchingData);
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
                        WHERE article.id_article = $id_article
                        GROUP BY article.id_article;
                        ;");
                        $matchingData  = $result->fetchAll(PDO::FETCH_ASSOC);
                    } else {
                        $result = excuteQuery("SELECT * FROM article ;");
                        $matchingData  = $result->fetchAll(PDO::FETCH_ASSOC);
                    }
                    // Envoi de la réponse au Client
                    deliver_response(200, "[GET] Bonjour, $name vous êtes publisher", $matchingData);
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
                    deliver_response(200, "[GET] Bonjour, vous n'avez pas de rôle", $matchingData);
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
            deliver_response(200, "[GET] Bonjour, vous n'êtes pas identifié", $matchingData);
            break;
        }
        break;
        /// Cas de la méthode POST
    case "POST":
        if (is_jwt_valid($data['token'], $secret)) {
            $role = getRoleFromToken($data['token']);
            $name = getLoginFromToken($data['token']);
            switch ($role) {
                case "publisher":
                    $id_moderator = getIdFromToken($data['token']);
                    if (!empty($data['titre']) && !empty($data['contenu'])) {
                        $titre = $data['titre'];
                        $contenu = $data['contenu'];
                        $date = date("Y-m-d H:i:s");
                        $result = excuteQuery("INSERT INTO article (titre, contenu, date_publication, id_utilisateur) VALUES ('$titre', '$contenu', '$date', '$id_moderator');");
                        $matchingData  = $result->fetchAll(PDO::FETCH_ASSOC);
                        // Envoi de la réponse au Client
                        deliver_response(200, "[POST] Bonjour $name, vous venez de créer un article", $result);
                        break;
                    } else {
                        deliver_response(200, "[POST] Bonjour $name, vous n'avez pas rempli tous les champs", NULL);
                        break;
                    }
                case "moderator":
                    deliver_response(200, "[POST] Bonjour $name, vous n'avez pas le droit de créer un article", NULL);
                    break;

                default:
                    deliver_response(200, "[POST] Bonjour $name, vous n'êtes pas connecté", NULL);
                    break;
            }
        } else {
            deliver_response(200, "[POST] Bonjour, vous n'êtes pas connecté", NULL);
            break;
        }
        break;

    case "PUT":
        //le publisher peut modifier les articles dont il est l’auteur.
        //le modérateur peut modifier aucun article.
        if (is_jwt_valid($data['token'], $secret)) {
            $role = getRoleFromToken($data['token']);
            $name = getLoginFromToken($data['token']);
            switch ($role) {
                case "publisher":
                    //recuperation de l'id de l'utilisateur
                    $id_publisher = getIdFromToken($data['token']);
                    $id_article = $_GET['id'];
                    if (!empty($data['titre']) && !empty($data['contenu'])) {
                        $titre = $data['titre'];
                        $contenu = $data['contenu'];
                        $date = date("Y-m-d H:i:s");
                        $result = excuteQuery("UPDATE article SET titre = '$titre', contenu = '$contenu', date_publication = '$date' WHERE id_article = $id_article AND id_utilisateur = $id_publisher;");
                        $matchingData  = $result->fetchAll(PDO::FETCH_ASSOC);
                        // Envoi de la réponse au Client
                        deliver_response(200, "[PUT] Bonjour $name, vous venez de modifier l'article $id_article", $matchingData);
                        break;
                    } else {
                        deliver_response(200, "[PUT] Bonjour $name, vous n'avez pas rempli tous les champs", NULL);
                        break;
                    }
                case "moderator":
                    deliver_response(200, "[PUT] Bonjour $name, vous n'avez pas le droit de modifier un article", NULL);
                    break;

                default:
                    deliver_response(200, "[PUT] Bonjour $name, vous n'êtes pas connecté", NULL);
                    break;
            }
        } else {
            deliver_response(200, "[PUT] Bonjour, vous n'êtes pas connecté", NULL);
            break;
        }
        break;

        /// Cas de la méthode DELETE
    case "DELETE":
        /// Récupération du body envoyé par le Client

        if (is_jwt_valid($data['token'], $secret)) {
            $role = getRoleFromToken($data['token']);
            $name = getLoginFromToken($data['token']);
            switch ($role) {
                case "moderator":
                    $id_article = $_GET['id_article'];
                    // Récupération de l'identifiant de la ressource envoyé par le Client
                    if (!empty($_GET['id_article'])) {
                        /// Traitement
                        $result = excuteQuery("DELETE liker, article
                        FROM liker
                        INNER JOIN article
                        ON article.id_article = liker.id_article
                        WHERE liker.id_article = $id_article;
                        DELETE FROM article WHERE article.id_article=$id_article ;
                        ");
                        /// Envoi de la réponse au Client
                        deliver_response(200, "[DELETE] Bonjour $name, Vous venez de supprimer l'article $id_article", NULL);
                        break;
                    } else {
                        deliver_response(200, "[DELETE] Impossible de supprimer l'article, elle n'éxiste pas", NULL);
                        break;
                    }
                case "publisher":
                    $id_article = $_GET['id_article'];
                    if (!empty($_GET['id_article'])) {
                        $id_publisher = getIdFromToken($data['token']);
                        //verification que l'article appartient bien au publisher
                        $result = excuteQuery("SELECT id_utilisateur FROM article WHERE id_article = $id_article");
                        $matchingData  = $result->fetchAll(PDO::FETCH_ASSOC);
                        if ($matchingData[0]['id_utilisateur'] == $id_publisher) {
                            $result = excuteQuery("DELETE liker, article
                            FROM liker
                            INNER JOIN article
                            ON article.id_article = liker.id_article
                            WHERE liker.id_article = $id_article;
                            DELETE FROM article WHERE article.id_article=$id_article;");
                            deliver_response(200, "[DELETE] Bonjour $name, Vous venez de supprimer l'article $id_article", NULL);
                            break;
                        } else {
                            deliver_response(200, "[DELETE] Impossible de supprimer l'article, vous n'êtes pas le proprietaire", NULL);
                            break;
                        }
                    } else {
                        deliver_response(200, "[DELETE] Impossible de supprimer l'article, l'id n'est pas renseigné", NULL);
                        break;
                    }

                default:
                    /// Envoi de la réponse au Client
                    deliver_response(200, "[DELETE] Impossible de supprimer, vous n'êtes pas identifié", NULL);
                    break;
            }
        } else {
            /// Envoi de la réponse au Client
            deliver_response(200, "[DELETE] Impossible de supprimer, vous n'êtes pas identifié", NULL);
            break;
        }
        break;

    case "LIKE":
        /*
        le publisher peut liker tout les articles sauf les siens.
        le modérateur ne peut pas liker tout les articles.
        */


        if (!empty($_GET['id_article'])) {
            if (is_jwt_valid($data['token'], $secret)) {
                $role = getRoleFromToken($data['token']);

                switch ($role) {
                    case "publisher":
                        $name = getLoginFromToken($data['token']);
                        $id_publisher = getIdFromToken($data['token']);
                        $id_article = $_GET['id_article'];
                        if (checkIfDislikeExist($id_publisher, $_GET['id_article'])) {
                            //mise a jours du like en dislike
                            $name = getLoginFromToken($data['token']);

                            $id_article = $_GET['id_article'];
                            $result = excuteQuery("UPDATE `liker` SET `type` = 'dislike' WHERE `liker`.`id_article` = $id_article AND `liker`.`id_utilisateur` = $id_publisher;");
                            deliver_response(200, "[DISLIKE] Bonjour $name, Vous venez de dislike l'article $id_article que vous aviez like au prealable", NULL);
                            break;
                        } else {
                            //verification que l'article appartient bien au publisher
                            $result = excuteQuery("SELECT id_utilisateur FROM article WHERE id_article = $id_article");
                            $matchingData  = $result->fetchAll(PDO::FETCH_ASSOC);
                            if ($matchingData[0]['id_utilisateur'] != $id_publisher) {
                                $result = excuteQuery("INSERT INTO `liker` (`id_article`, `id_utilisateur`, `type`) VALUES ('$id_article', '$id_publisher', 'like');");

                                deliver_response(200, "[LIKE] Bonjour $name, Vous venez de liker l'article $id_article", NULL);
                                break;
                            } else {
                                deliver_response(200, "[LIKE] impossible de liker l'article, vous etes le proprietaire", NULL);
                                break;
                            }
                        }
                    case "moderator":
                        deliver_response(200, "[LIKE] Bonjour $name, vous n'avez pas le droit de liker un article vous n'etes pas publisher", NULL);
                        break;

                    default:
                        deliver_response(200, "[LIKE] Bonjour $name, vous n'etes pas connecté", NULL);
                        break;
                }
            } else {
                deliver_response(200, "[LIKE] Bonjour, vous n'etes pas connecté", NULL);
                break;
            }
        } else {
            deliver_response(200, "[LIKE] Bonjour, vous n'avez pas renseigné l'id de l'article", NULL);
            break;
        }
        break;
    case "DISLIKE":
        /*
        le publisher peut liker tout les articles sauf les siens.
        le modérateur ne peut pas liker tout les articles.
        */
        /// Récupération du body envoyé par le Client

        if (!empty($_GET['id_article'])) {
            if (is_jwt_valid($data['token'], $secret)) {
                $role = getRoleFromToken($data['token']);
                $id_publisher = getIdFromToken($data['token']);
                switch ($role) {
                    case "publisher":
                        if (checkIfLikeExist($id_publisher, $_GET['id_article'])) {
                            //mise a jours du like en dislike
                            $name = getLoginFromToken($data['token']);

                            $id_article = $_GET['id_article'];
                            $result = excuteQuery("UPDATE `liker` SET `type` = 'dislike' WHERE `liker`.`id_article` = $id_article AND `liker`.`id_utilisateur` = $id_publisher;");
                            deliver_response(200, "[DISLIKE] Bonjour $name, Vous venez de dislike l'article $id_article que vous aviez like au prealable", NULL);
                            break;
                        } else {
                            $name = getLoginFromToken($data['token']);
                            $id_publisher = getIdFromToken($data['token']);
                            $id_article = $_GET['id_article'];
                            //verification que l'article appartient bien au publisher
                            $result = excuteQuery("SELECT id_utilisateur FROM article WHERE id_article = $id_article");
                            $matchingData  = $result->fetchAll(PDO::FETCH_ASSOC);
                            if ($matchingData[0]['id_utilisateur'] != $id_publisher) {
                                $result = excuteQuery("INSERT INTO `liker` (`id_article`, `id_utilisateur`, `type`) VALUES ('$id_article', '$id_publisher', 'dislike');");

                                deliver_response(200, "[DISLIKE] Bonjour $name, Vous venez de dislike l'article $id_article", NULL);
                                break;
                            } else {
                                deliver_response(200, "[DISLIKE] impossible de dislike l'article, vous etes le proprietaire", NULL);
                                break;
                            }
                        }
                    case "moderator":
                        deliver_response(200, "[DISLIKE] Bonjour $name, vous n'avez pas le droit de dislike un article", NULL);
                        break;

                    default:
                        deliver_response(200, "[DISLIKE] Bonjour $name, vous n'etes pas connecté", NULL);
                        break;
                }
            } else {
                deliver_response(200, "[DISLIKE] Bonjour, vous n'etes pas connecté", NULL);
                break;
            }
        } else {
            deliver_response(200, "[DISLIKE] Bonjour, vous n'avez pas renseigné l'id de l'article", NULL);
            break;
        }

        break;

    default:
        /// Envoi de la réponse au Client
        deliver_response(200, "[Default] Bonjour, vous n'avez pas renseigné de méthode", NULL);
        break;
}
/// Envoi de la réponse au Client
