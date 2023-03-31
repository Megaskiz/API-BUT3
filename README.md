PROJET API REST

Binome BEZARA Jonathan, MENDES  Adam
Groupe C

Projet : 
Le projet vise à fournir une solution complète de gestion d'articles de blogs, basée sur une ou plusieurs API REST.
Nous nous concentrerons sur le développement du backend et utiliserons Postman comme client. 

Pour les différents comtpes pour accèder :

moderator : 
identifiant : Admin         identifiant : Test
mdp : admin                 mdp : azerty


publisher : 
identifiant : Alice         identifiant : Bob       identifiant : Carl
mdp : Alice123              mdp : Bob123            mdp : Carl123


Si vous souhaitez importer des données dans la base de données :

Articles: 
- INSERT INTO `article` (`id_article`, `titre`, `contenu`, `date_publication`, `id_utilisateur`) VALUES
('id_article', 'Titre', 'contenu', '0000-00-00', 'id_utilisateur');

Utilisateurs : 
- un moderator ('id_utilisateur', 'Test', 'mdp', 'moderator');
- un publisher ('id_utilisateur', 'Nom', 'mdp', 'publisher');


IMPORT LA BD PhpMyAdmin :
- vous créez un nouvelle BD appelée : "projetapi"
- vous aller dans l'onglet 'import' en haut de la page 
- ajoutez le fichier "projetapi.bd" qui se situe dans le projet /bd/projetapi.bd






	
