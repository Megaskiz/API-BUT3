<?php
/*
Serveur d'authentification capable de générer des jetons JWT. 
Ce serveur est une API REST qui ne traite que les requêtes correspondant à une méthode HTTP POST. 
Lorsque l'API reçoit des identifiants (login/mot de passe), elle les compare à des valeurs stockées en dur dans le code de l'API, et retourne un jeton JWT si les identifiants sont valides.
*/


//fichier contenant les fonctions
include('fonction.php');

//fichier contenant les fonctions de génération de jeton
include('jwt_utils.php');

/// Paramétrage de l'entête HTTP (pour la réponse au Client)
header("Content-Type:application/json");
/// Identification du type de méthode HTTP envoyée par le client
$http_method = $_SERVER['REQUEST_METHOD'];

//switch pour le choix de la méthode HTTP
$secret="HereistherestapiofadamandjonathaninphpfortheresourceR4.01";
switch ($http_method) {

    /// Cas de la méthode POST
    case "POST":
        ///Récupération des données envoyées par le client
        $postedData = file_get_contents('php://input');
        $data = json_decode($postedData, true);

        $login = $data['login'];
        $password = $data['password'];

        //verification des identifiants
        if(validLogin($login, $password)){
            $id = getId($login);
            $role = getRole($login);

            //création du jeton jwt
            $headers=array('alg'=>'HS256', 'typ'=>'JWT');
            $playload=array('user'=>$login,'role'=>$role, 'id'=>$id, 'exp'=>(time()+3600));
            $token = generate_jwt($headers, $playload,$secret);

            //envoi de la réponse au client avec le jeton jwt
            deliver_response(200, "[200 API REST] le identifiant sont valide", $token);
            
        }else{
            //envoi de la réponse au client si les identifiants sont invalides
            deliver_response(401, "[401 API REST] les identifiants sont invalides", NULL);
        }

        break;

    default:
        //envoi de la réponse au client si la méthode HTTP n'est pas POST
        deliver_response(405, "[API REST] Mauvaise méthode choisie, veuillez utiliser la méthode POST pour se connecter", NULL);
        break;
}

