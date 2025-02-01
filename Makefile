phpdocker=api_toubeelib_gt
phpdockerPraticien=api_toubeelib_praticien
phpdockerRdv=api_toubeelib_rdv
phpdockerAuth=api_toubeelib_auth
install: 
	make up
	make composer
	make genereDb
	make genereDbPraticien
	make genereDbRdv
up:
	docker compose up -d --remove-orphans --build
composer:
	docker exec -it $(phpdocker) composer install
	docker exec -it gateway_toubeelib composer install
	docker compose run --rm api.toubeelib.rdv composer install
	docker compose run --rm api.toubeelib.praticiens composer install
	docker compose run --rm api.toubeelib.auth composer install
genereDb:
	docker exec -it $(phpdocker) php ./src/infrastructure/genereAuthDb.php
	docker exec -it $(phpdocker) php ./src/infrastructure/genereDB.php
genereDbPraticien:
	docker exec -it $(phpdockerPraticien) php ./src/infrastructure/genereDB.php
genereDbRdv:
	docker exec -it $(phpdockerRdv) php ./src/infrastructure/genereDB.php
genererDbAuth: 
	docker exec -it $(phpdockerAuth) php ./src/infrastructure/genereDB.php
watchLogs:
	watch -n 2 tail app/var/logs
confFiles:
	cp ./toubeelib.env.dist ./toubeelib.env 
	cp ./toubeelibdb.env.dist ./toubeelibdb.env 
	cp ./toubeelibauthdb.env.dist ./toubeelibauthdb.env
	cp ./app/config/pdoConfig.ini.dist ./app/config/pdoConfig.ini
	cp ./app/config/pdoConfigAuth.ini.dist ./app/config/pdoConfigAuth.ini
