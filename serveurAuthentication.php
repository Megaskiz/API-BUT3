<?php
/*
Serveur d'authentification capable de générer des jetons JWT. 
Ce serveur est une API REST qui ne traite que les requêtes correspondant à une méthode HTTP POST. 
Lorsque l'API reçoit des identifiants (login/mot de passe), elle les compare à des valeurs stockées en dur dans le code de l'API, et retourne un jeton JWT si les identifiants sont valides.
*/
include('fonction.php');
include('jwt_utils.php');
/// Paramétrage de l'entête HTTP (pour la réponse au Client)
header("Content-Type:application/json");
/// Identification du type de méthode HTTP envoyée par le client
$http_method = $_SERVER['REQUEST_METHOD'];

switch ($http_method) {

        /// Cas de la méthode POST
    case "POST":
        //traitement
        $postedData = file_get_contents('php://input');
        $data = json_decode($postedData, true);

        $login = $data['login'];
        $password = $data['password'];

        if(validLogin($login, $password)){
            $role = getRole($login);
            $headers=array('alg'=>'HS256', 'typ'=>'JWT');
            $playload=array('user'=>$login,'role'=>$role, 'exp'=>(time()+3600));
            $token = generate_jwt($headers, $playload);
            deliver_response(200, "[200 API REST] le token est valide", $token);
        }else{
            deliver_response(401, "[401 API REST] les identifiants sont invalides", NULL);
        }

        break;

    default:
        deliver_response(405, "[401 API REST] POST bad request", NULL);
        break;
}

