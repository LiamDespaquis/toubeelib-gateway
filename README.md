# Toubeelib GATEWAY
DAZA Sasha
DESPAQUIS Liam
PINOT Gaëtan

## Installation

TLDR: `make confFiles && make chmodLog && make install` (Tant que les ports par défaut du `.env` ne sont pas utilisés)  


Fichier de config à créer (copier le fichier .dist existant en enlevant .dist):  
(Ils peuvent aussi êtres copié diréctement avec `make confFiles`)  
- `.env` Contient les variables d'environnement pour les ports des services
- `amqp.env`
- `mailer.env`
- `toubeelibauthdb.env`
- `toubeelibdb.env`
- `toubeelib.env`
- `./app-rdv/config/pdoConfig.ini`
- `./app-rdv/config/pdoConfigAuth.ini` (ne sont pas utile en prod, juste pour accéder au id d'users dans l'authdb pour créer des données de test dans la db)
- `./app-praticiens/config/pdoConfig.ini`
- `./app-praticiens/config/pdoConfigAuth.ini`(ne sont pas utile en prod, juste pour accéder au id d'users dans l'authdb pour créer des données de test dans la db)
- `./app-auth/config/pdoConfigAuth.ini`  
Les mots de passes doivent être consistant dans la db  


Utiliser `make install` pour installer le projet de zero avec composer dans le docker, les autres commandes makes peuvent être utilisés individuellement. 
`make composerUpdate` pour mettre à jour les dépendances.  


Il serat peut être nécéssaires de faire un `make chmodLog` pour que les fichier de logs aient les bonnes permissions dans le docker.  

## Description

Il y à 3 services, un pour les praticiens, un pour les rendez vous et un pour l'authentification.
Celui pour les rendez vous sert aussi pour les patients car jugé non nécéssaire pour l'exercice étant donné qu'on en a déjà créé 3.

## Tests

Le dossier `./brunoGateway/`, à ouvrir avec [Bruno](https://www.usebruno.com), contient une collection de requete.  
Elles permettent de tester l'api, elles contiennent (presque toutes) les données nécéssaires pour tester l'api.  
Quand on ouvre la collection pour la première fois, il faut selectionner l'environnement de collection (`env_dev`) pour que les urls soit les bons.  
Certaines requêtes contiennes des assertions ou des tests, qui sont pratique pour verifier que l'api fonctionne correctement avec un runner qui fait toues les requettes.
Cependant nous n'en avons pas fait pour toutes les requêtes.  
Il faut commencer par executer une requête de connexion, en tant que praticien ou patient selon ce que l'on veut tester.  
Il faut ajuster les requettes avecs des ids de rendez vous car ils sont regénéré aléatoirement a chaque foit qu'on regénère la base de donnée.  
Il faut aussi ajuster les heures de creation du rdv en fonction des disponibilité du praticien.  


