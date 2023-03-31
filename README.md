PROJET API REST/R4.01

Binome BEZARA Jonathan, MENDES  Adam
Groupe C

PROJET : 

Le projet vise à fournir une solution complète de gestion d'articles de blogs, basée sur une ou plusieurs API REST.
Nous nous concentrerons sur le développement du backend et utiliserons Postman comme client. 

La procédure de connexion sur POSTMAN est dans la documentation.

ACCEDER A L'APPLICATION :

moderator : 

-identifiant : Admin         
-mdp : admin  

-identifiant : Test              
-mdp : azerty


publisher : 

-identifiant : Alice              
-mdp : Alice123                         

-identifiant : Carl
-mdp : Carl123

-identifiant : Bob
-mdp : Bob123



VOUS VOULEZ IMPORTER DES DONNEES DANS LA BD :

Articles: 
- INSERT INTO `article` (`id_article`, `titre`, `contenu`, `date_publication`, `id_utilisateur`) VALUES
('id_article', 'Titre', 'contenu', '0000-00-00', 'id_utilisateur');

Utilisateurs : 

- INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `mdp`, `role`) VALUES
    - un moderator: ('id_utilisateur', 'Test', 'mdp', 'moderator');
    - un publisher: ('id_utilisateur', 'Nom', 'mdp', 'publisher');


IMPORTER LA BD DANS PhpMyAdmin :
- vous créez un nouvelle BD appelée : "projetapi"
- vous aller dans l'onglet 'import' en haut de la page 
- ajoutez le fichier "projetapi.bd" qui se situe dans le projet /bd/projetapi.bd



