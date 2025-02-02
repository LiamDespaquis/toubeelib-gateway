<?php
// docker exec -it toubeelib-api.toubeelib-1 php src/infrastructure/genereAuthDB.php
require_once __DIR__ .'/../../vendor/autoload.php';


$config= parse_ini_file(__DIR__.'/../../config/pdoConfigAuth.ini');
$co = new PDO($config['driver'].':host='.$config['host'].';port='.$config['port'].';dbname='.$config['dbname'].';user='.$config['user'].';password='.$config['password']);
$schema = file_get_contents('/var/sql/toubee_auth.schema.sql');
$data = file_get_contents('/var/sql/toubee_auth.data.sql');
$co->exec($schema);
$co->exec($data);
