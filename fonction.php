<?php
//fonction php qui ce connecte a la base de donnees
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

//fontion php qui envoie une reponse au format json
function deliver_response($status, $status_message, $data)
{
    /// Paramétrage de l'entête HTTP, suite
    header("HTTP/1.1 $status $status_message");
    /// Paramétrage de la réponse retournée
    $response['status'] = $status;
    $response['status_message'] = $status_message;
    $response['data'] = $data;

    /// Mapping de la réponse au format JSON
    $json_response = json_encode($response);
    echo $json_response;
}

//fonction php qui permet de verifier si un utilisateur et inscrit dans la base de donnee avec son login et son mot de passe
function validLogin($login, $password)
{
    $validLogin = false;
    $result = excuteQuery("SELECT * FROM utilisateur WHERE nom = '$login' AND mdp = '$password';");
    if ($result->rowCount() > 0) {
        $validLogin = true;
    }
    return $validLogin;
}
//fonction php qui permet d'executer une requete sql
function excuteQuery($sql)
{
    $bd=connecter_bd();
    try {
        $result = $bd->query($sql);
        $bd = null;
        return $result;
    } catch (PDOException $e) {
        echo '{"error":{"text":' . $e->getMessage() . '}}';
    }
}
//fonction php qui permet de recuperer le role d'un utilisateur a partir de son login
function getRole($login){
    $Tab_role = excuteQuery("SELECT role FROM utilisateur WHERE nom = '$login'");
    $role = $Tab_role->fetch();
    return $role[0];
}
//fonction php qui permet de recuperer l'id d'un utilisateur a partir de son login
function getId($login){
    $Tab_id = excuteQuery("SELECT id_utilisateur FROM utilisateur WHERE nom = '$login'");
    $id = $Tab_id->fetch();
    return $id[0];
}
//fonction php qui permet de recuperer le payload d'un token
function getPlaylod($jwt){
    $tokenParts = explode('.', $jwt);
    $payload = base64_decode($tokenParts[1]);
    return $payload;
}
//fonction php qui permet de recuperer le login d'un utilisateur a partir de son token
function getLoginFromToken($jwt){
    $payload = getPlaylod($jwt);
    $login = json_decode($payload)->user;
    return $login;
}
//fonction php qui permet de recuperer le role d'un utilisateur a partir de son token
function getRoleFromToken($jwt){
    $payload = getPlaylod($jwt);
    $role = json_decode($payload)->role;
    return $role;
}
//fonction php qui permet de recuperer l'id d'un utilisateur a partir de son token
function getIdFromToken($jwt){
    $payload = getPlaylod($jwt);
    $id = json_decode($payload)->id;
    return $id;
}

//fonction php qui permet de verifier si un utilisateur a deja like un article
function checkIfLikeExist($id_user, $id_post){
    $result = excuteQuery("SELECT * FROM `liker` WHERE id_utilisateur = $id_user AND id_article = $id_post");
    if ($result->rowCount() > 0) {
        return true;
    }
    return false;

}

//fonction php qui permet de verifier si un utilisateur a deja dislike un article
function checkIfDislikeExist($id_user, $id_post){
    $result = excuteQuery("SELECT * FROM `disliker` WHERE id_utilisateur = $id_user AND id_article = $id_post");
    if ($result->rowCount() > 0) {
        return true;
    }
    return false;

}
