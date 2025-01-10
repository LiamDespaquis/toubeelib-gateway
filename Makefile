phpdocker=api_toubeelib
install: 
	make up
	make composer
	make genereDb
up:
	docker compose up -d --remove-orphans --build
composer:
	docker exec -it $(phpdocker) composer install
	docker exec -it gateway_toubeelib composer install
genereDb:
	docker exec -it $(phpdocker) php ./src/infrastructure/genereAuthDb.php
	docker exec -it $(phpdocker) php ./src/infrastructure/genereDB.php
watchLogs:
	watch -n 2 tail app/var/logs
confFiles:
	cp ./toubeelib.env.dist ./toubeelib.env 
	cp ./toubeelibdb.env.dist ./toubeelibdb.env 
	cp ./toubeelibauthdb.env.dist ./toubeelibauthdb.env
	cp ./app/config/pdoConfig.ini.dist ./app/config/pdoConfig.ini
	cp ./app/config/pdoConfigAuth.ini.dist ./app/config/pdoConfigAuth.ini
