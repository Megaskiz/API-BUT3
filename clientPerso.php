<head>
    <meta charset="utf-8">
    <title>Chuckn Facts</title>
    <link rel="stylesheet" href="style.css">
</head>
<h1>Fun Fact Chuck Norris</h1>
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

//requete d'ajout de fact
if (isset($_GET['phrase'])) {
    $data = array("phrase" => $_GET['phrase'], "token" => $token);
    $data_string = json_encode($data);
    $result = file_get_contents(
        'http://localhost:8080/API_REST/REST/Perso/serveurREST.php',
        false,
        stream_context_create(array(
            'http' => array(
                'method' => 'POST', // ou PUT
                'content' => $data_string,
                'header' => array('Content-Type: application/json' . "\r\n"
                    . 'Content-Length: ' . strlen($data_string) . "\r\n")
            )
        ))
    );
    header('Location: clientPerso.php');
}

?>
<form action="">
    <input type="text" name="phrase" placeholder="phrase">
    <input type="submit" value="Ajouter">
</form>

<?php
////////////////// Cas des méthodes GET //////////////////
$data = file_get_contents(
    'http://localhost:8080/API_REST/REST/Perso/serveurREST.php',
    false,
    stream_context_create(array('http' => array('method' => 'GET'))) // ou DELETE
);

$json = json_decode($data, true);
?>

<table>
    <tr>
        <th>ID</th>
        <th>Fact</th>
        <th>Modifier</th>
        <th>Supprimer</th>
    </tr>
    <?php foreach ($json['data'] as $fact) { ?>
        <tr>
            <td><?php echo $fact['id']; ?></td>
            <td><?php echo $fact['phrase']; ?></td>
            <td><a href="updateChucknFactPerso.php?id=<?php echo $fact['id']; ?>"> <button>Modifier</button> </a></td>
            <td><a href="deleteChucknFactPerso.php?id=<?php echo $fact['id']; ?>"> <button>Supprimer</button> </a></td>
        </tr>
    <?php } ?>
</table>


<?php

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