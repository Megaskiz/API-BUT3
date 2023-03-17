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

function validLogin($login, $password)
{
    $validLogin = false;
    $result = excuteQuery("SELECT * FROM utilisateur WHERE nom = '$login' AND mdp = '$password';");
    if ($result->rowCount() > 0) {
        $validLogin = true;
    }
    return $validLogin;
}
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
function getRole($login){
    $Tab_role = excuteQuery("SELECT role FROM utilisateur WHERE nom = '$login'");
    $role = $Tab_role->fetch();
    return $role[0];
}

function getPlaylod($jwt){
    $tokenParts = explode('.', $jwt);
    $payload = base64_decode($tokenParts[1]);
    return $payload;
}

function getLoginFromToken($jwt){
    $payload = getPlaylod($jwt);
    $login = json_decode($payload)->user;
    return $login;
}
function getRoleFromToken($jwt){
    $payload = getPlaylod($jwt);
    $role = json_decode($payload)->role;
    return $role;
}
/*function is_logged()
{
    session_start();
    $tokenParts = explode('.', $jwt);
    $payload = base64_decode($tokenParts[1]);
    $expiration = json_decode($payload)->exp;
	$is_token_expired = ($expiration - time()) < 0;

    if($is_token_expired){
        session_destroy();
        header("Location: login.php");
    }


}*/