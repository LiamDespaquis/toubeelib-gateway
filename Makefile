install: 
	make up
	make composerInstalle
	make genereDbPraticien
	make genereDbRdv
	make genererDbAuth

up:
	docker compose up -d --remove-orphans --build

composerUpdate:
	docker compose run --rm api.toubeelib.rdv composer update
	docker compose run --rm api.toubeelib.praticiens composer update
	docker compose run --rm api.toubeelib.auth composer update
	docker compose run --rm mailer composer update
	docker compose run --rm gateway.toubeelib composer update

composerInstalle:
	docker compose run --rm api.toubeelib.rdv composer install
	docker compose run --rm api.toubeelib.praticiens composer install
	docker compose run --rm api.toubeelib.auth composer install
	docker compose run --rm mailer composer install
	docker compose run --rm gateway.toubeelib composer install

genereDbPraticien:
	docker compose exec  api.toubeelib.praticiens php ./src/infrastructure/genereDB.php

genereDbRdv:
	docker compose exec  api.toubeelib.rdv php ./src/infrastructure/genereDB.php

genererDbAuth: 
	docker compose exec  api.toubeelib.auth php ./src/infrastructure/genereDB.php

watchLogs:
	watch -n 2 tail app/var/logs

confFiles:
	cp ./.env.dist ./.env
	cp ./toubeelib.env.dist ./toubeelib.env 
	cp ./toubeelibdb.env.dist ./toubeelibdb.env 
	cp ./toubeelibauthdb.env.dist ./toubeelibauthdb.env
	cp ./amqp.env.dist ./amqp.env
	cp ./mailer.env.dist ./mailer.env
	cp ./app-rdv/config/pdoConfig.ini.dist ./app-rdv/config/pdoConfig.ini
	cp ./app-praticiens/config/pdoConfig.ini.dist ./app-praticiens/config/pdoConfig.ini
	cp ./app-auth/config/pdoConfigAuth.ini.dist ./app-auth/config/pdoConfigAuth.ini
