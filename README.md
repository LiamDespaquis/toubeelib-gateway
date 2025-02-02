# Toubeelib GATEWAY
DAZA Sasha
DESPAQUIS Liam
PINOT Gaëtan

## Config
Fichier de config à créer (copier le fichier .dist existant en enlevant .dist):  
(Ils peuvent aussi êtres copié diréctement avec `make confFiles`)  
- `amqp.env`
- `mailer.env`
- `toubeelibauthdb.env`
- `toubeelibdb.env`
- `toubeelib.env`
- `./app-rdv/config/pdoConfig.ini`
- `./app-praticiens/config/pdoConfig.ini`
- `./app-auth/config/pdoConfigAuth.ini`  
Les mots de passes doivent être consistant dans la db  
Utiliser `make install` pour installer le projet de zero avec composer dans le docker, les autres commandes makes peuvent être utilisés individuellement. 
`make composerUpdate` pour mettre à jour les dépendances.  

## Tests

Le dossier `./brunoGateway/`, à ouvrir avec [Bruno](https://www.usebruno.com), contient une collection de requete.  
Elles permettent de tester l'api, elles contiennent (presque toutes) les données nécéssaires pour tester l'api.  
Quand on ouvre la collection pour la première fois, il faut selectionner l'environnement de collection (`env_dev`) pour que les urls soit les bons.  
Certaines requêtes contiennes des assertions ou des tests, qui sont pratique pour verifier que l'api fonctionne correctement avec un runner qui fait toues les requettes.
Cependant nous n'en avons pas fait pour toutes les requêtes.  
Il faut commencer par executer une requête de connexion, en tant que praticien ou patient selon ce que l'on veut tester.  
Il faut ajuster les requettes avecs des ids de rendez vous car ils sont regénéré aléatoirement a chaque foit qu'on regénère la base de donnée.  
Il faut aussi ajuster les heures de creation du rdv en fonction des disponibilité du praticien.  


## TODO
~.env dans maekfile~
.ini dans les micro service qui pointe vers les bonnes bd

