<?php
//récupaération du token avec la fonction tokenRequest
if (isset($_POST['login']) && isset($_POST['password'])) {
    $login = $_POST['login'];
    $password = $_POST['password'];
    $token = tokenRequest($login, $password);
    if ($token == null) {
        header('Location: page_connection.php?error=1');
    }else{
        //stocker le token dans une variable session
        session_start();
        $_SESSION['token'] = $token;
       header('Location:clien.php');
    }
}


function tokenRequest($login, $password)
{
    $data = array("login" => $login, "password" => $password);
    $data_string = json_encode($data);
    $result = file_get_contents(
        'http://localhost/projet_api/serveurAuthentication.php',
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
    var_dump($json);
    $token = $json['data'];
    return $token;
}
?>