<?php
//récupaération du token avec la fonction tokenRequest
if (isset($_GET['login']) && isset($_GET['password'])) {
    $login = $_GET['login'];
    $password = $_GET['password'];
    $token = tokenRequest($login, $password);
    if ($token == null) {
        header('Location: login.php');
    }
}

function tokenRequest($login, $password)
{
    $data = array("login" => $login, "password" => $password);
    $data_string = json_encode($data);
    $result = file_get_contents(
        'http://localhost:8080/API_REST/REST/Perso/serveurAuthentication.php',
        false,
        stream_context_create(array(
            'http' => array(
                'method' => 'POST',
                'content' => $data_string,
                'header' => array('Content-Type: application/json' . "\r\n"
                    . 'Content-Length: ' . strlen($data_string) . "\r\n")
            )
        ))
    );

    //stocker le token dans une variable token
    $json = json_decode($result, true);
    $token = $json['data'];

    return $token;
}
?>