# Toubeelib GATEWAY
DAZA Sasha
DESPAQUIS Liam
PINOT Gaëtan

## Config
Fichier de config à créer (copier le fichier .dist existant en enlevant .dist):

- `amqp.env`
- `mailer.env`
- `toubeelibauthdb.env`
- `toubeelibdb.env`
- `toubeelib.env`
- `./app-rdv/config/pdoConfig.ini`
- `./app-praticiens/config/pdoConfig.ini`
- `./app-auth/config/pdoConfigAuth.ini`  
Ils peuvent aussi êtres copié diréctement avec `make confFiles`  
Les mots de passes doivent être consistant dans la db  
Utiliser `make install` pour installer le projet de zero avec composer dans le docker, les autres commandes makes peuvent être utilisés individuellement
## Tests

Le dossier `./testsApi/`, à ouvrir avec [Bruno](https://www.usebruno.com), contient une collection de requete.  
Elle permette de tester l'api, elle contienne les données nécéssaires pour tester l'api.  
Il faut commencer par executer une requête de connexion, en tant que praticien ou patient selon ce que l'on veut tester.  
Il faut ajuster les requettes avecs des ids de rendez vous car ils sont regénéré aléatoirement a chaque foit qu'on regénère la base de donnée.  
Il faut aussi ajuster les heures de creation du rdv en fonction des disponibilité du praticien.  

## Toubeelib, architecture générale (noté sur 10 points) :
- [x] API respectant les principes RESTful : désigation des ressources (URIs), opérations et méthodes HTTP adéquates, status de retours corrects, données échangées au format JSON, incluant des liens HATEOAS, Gaëtan
- [x] architecture basée sur les principes d’architecture Hexagonale et d’inversion de dépendances, en particulier pour les bases de données, Gaëtan
- [x] utilisation d’un conteneur d’injection de dépendances, Gaëtan
- [x] traitement des erreurs et exceptions, Liam (en partie)
- [x] traitement des headers CORS,
- [x] authentification à l’aide de tokens JWT, Gaëtan
- [x] utilisation adéquate des mécanismes du framework Slim, notamment les middlewares,Gaëtan
- [x] validation et filtrage des données reçues au travers de l’API,Gaëtan
- [x] utilisation de bases de données distinctes pour les patients, pour les RDV, pour les praticiens et ce qui s’y rattache, et pour l’authentification. Ces bases de données pourront éventuellement être gérées dans des conteneurs Docker différents. Gaëtan
## Les fonctionnalités minimales attendues (notées sur 6 points) :
- [x] lister/rechercher des praticiens, Gaëtan
- [x] lister les disponibilités d’un praticien sur une période donnée (date de début, date de fin), Sasha
- [x] réserver un rendez-vous pour un praticien à une date/heure donnée, Liam
- [x] annuler un rendez-vous, à la demande d’un patient ou d’un praticien, Gaëtan
- [x] gérer le cycle de vie des rendez-vous (honoré, non honoré, payé), Sasha (en partie),
- [x] afficher le planning d’un praticien sur une période donnée (date de début, date de fin) en précisant la spécialité concernée et le type de consultation (présentiel, téléconsultation), Liam
- [x] afficher les rendez-vous d’un patient, Liam
- [x] s’authentifier en tant que patient ou praticien. Gaëtan
## Les fonctionnalités additionnelles attendues (notées sur 4 points) :
- [ ] créer un praticien,
- [ ] s’inscrire en tant que patient
- [ ] gérer les indisponibilités d’un praticien : périodes ponctuelles sur lesquelles il ne peut accepter de RDV,
- [ ] gérer les disponibilités d’un praticien : jours, horaires et durée des RDV pour chaque praticien,

