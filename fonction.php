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
    $result = excuteQuery("SELECT * FROM utilisateur WHERE nom = '$login' AND mdp = '$password'");
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