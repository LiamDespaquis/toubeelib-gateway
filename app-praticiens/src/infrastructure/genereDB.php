<?php
// docker exec -it toubeelib-api.toubeelib-1 php src/infrastructure/genereDB.php
$nbPraticien=50;
$nbPatient=400;
$nbRdv=240;
require_once __DIR__ .'/../../vendor/autoload.php';

use toubeelib\praticiens\core\domain\entities\rdv\RendezVous;
use toubeelib\praticiens\core\services\rdv\ServiceRDV;

$drop='drop table if exists praticien;';
$cspecialite='
create table specialite(
id varchar(5),
label varchar(50) not null,
description text not null,
primary key(id)
);
';
$cpraticien='
create table praticien(
id UUID,
rpps varchar(50) not null,
nom varchar(50) not null,
prenom varchar(50) not null,
adresse varchar(100) not null,
tel varchar(20) not null,
specialite varchar(5) not null,
primary key(id),
foreign key(specialite) references specialite(id)
);
';




$config= parse_ini_file(__DIR__.'/../../config/pdoConfig.ini');
$co = new PDO($config['driver'].':host='.$config['host'].';port='.$config['port'].';dbname='.$config['dbname'].';user='.$config['user'].';password='.$config['password']);
try{
	$config = parse_ini_file(__DIR__.'/../../config/pdoConfigAuth.ini');
	$coAuth = new PDO($config['driver'].':host='.$config['host'].';port='.$config['port'].';dbname='.$config['dbname'].';user='.$config['user'].';password='.$config['password']);
}catch(Exception $e){
	echo("Connexion à la base auth impossible, veuillez verifier si elle existe bien\n");
	throw $e;
}


//on recupère les praticiens
$query = "select id,email from users where role = 10";
$praticiensIds= $coAuth->prepare($query);
$praticiensIds->execute();
$praticiensIds = $praticiensIds->fetchAll(PDO::FETCH_ASSOC);


$res=$co->exec($cspecialite);
$res=$co->exec($cpraticien);

$faker = Faker\Factory::create('fr_FR');

// specialite
// id varchar(5),
// label varchar(50) not null,
// description text not null,
// primary key(id)

$spe = [
	'A' => [
		'ID' => 'A',
		'label' => 'Dentiste',
		'description' => 'Spécialiste des dents'
	],
	'B' => [
		'ID' => 'B',
		'label' => 'Ophtalmologue',
		'description' => 'Spécialiste des yeux'
	],
	'C' => [
		'ID' => 'C',
		'label' => 'Généraliste',
		'description' => 'Médecin généraliste'
	],
	'D' => [
		'ID' => 'D',
		'label' => 'Pédiatre',
		'description' => 'Médecin pour enfants'
	],
	'E' => [
		'ID' => 'E',
		'label' => 'Médecin du sport',
		'description' => 'Maladies et trausmatismes liés à la pratique sportive'
	],
];
$speIds=[];
$query='insert into specialite (id, label, description) values (:ID, :label, :description);';
$insert= $co->prepare($query);
foreach($spe as $s){
	$insert->execute($s);
	$speIds[]=$s['ID'];
}

//praticien
// id UUID,
// rpps varchar(50) not null,
// nom varchar(50) not null,
// prenom varchar(50) not null,
// adresse varchar(100) not null,
// tel varchar(15) not null,
// specialite varchar(5) not null,
// $praticienIds=[];
$query='insert into praticien (id, rpps, nom, prenom, adresse, tel, specialite) 
values(:id, :rrps, :nom, :prenom, :adresse, :tel, :specialite);';
$insert = $co->prepare($query);
foreach($praticiensIds as $pra){
	$val=[
		'id'=> $pra['id'],
		'rrps'=> $faker->numberBetween(100000,999999),
		'prenom'=> $faker->firstName(),
		'nom'=>$faker->lastName(),
		'adresse'=>$faker->address(),
		'tel'=>$faker->phoneNumber(),
		'specialite'=>$speIds[$faker->numberBetween(0,count($speIds)-1)]
	];
	$insert->execute($val);

}

echo $erreurEvite."\r\n";

