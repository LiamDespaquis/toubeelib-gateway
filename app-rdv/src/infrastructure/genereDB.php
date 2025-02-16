<?php

// docker exec -it toubeelib-api.toubeelib-1 php src/infrastructure/genereDB.php
$nbPraticien = 50;
$nbPatient = 400;
$nbRdv = 240;
require_once __DIR__ .'/../../vendor/autoload.php';

use toubeelib\rdv\core\domain\entities\rdv\RendezVous;
use toubeelib\rdv\core\services\rdv\ServiceRDV;

$drop = 'drop table if exists status,rdv,patient;';
$cstatus = '
create table status(
id int,
label varchar(50) not null,
primary key(id)
);
';
$cpatient = '
create table patient(
id UUID,
nom varchar(50) not null,
prenom varchar(50) not null,
dateNaissance date not null,
adresse varchar(100),
tel varchar(20),
mail varchar(100),
idMedcinTraitant UUID,
numSecuSociale varchar(50),
primary key(id)
);
';
$crdv = '
create table rdv(
id UUID,
date timestamp,
patientId UUID,
praticienId UUID,
status int,
primary key(id),
foreign key(status) references status(id),
foreign key(patientId) references patient(id)
);
';


$config = parse_ini_file(__DIR__.'/../../config/pdoConfig.ini');
$co = new PDO($config['driver'].':host='.$config['host'].';port='.$config['port'].';dbname='.$config['dbname'].';user='.$config['user'].';password='.$config['password']);
try {
    $config = parse_ini_file(__DIR__.'/../../config/pdoConfigAuth.ini');
    $coAuth = new PDO($config['driver'].':host='.$config['host'].';port='.$config['port'].';dbname='.$config['dbname'].';user='.$config['user'].';password='.$config['password']);
} catch(Exception $e) {
    echo("Connexion à la base auth impossible, veuillez verifier si elle existe bien\n");
    throw $e;
}

//on recupère les ids des patients
$query = "select id,email from users where role = 0;";
$patientsIds = $coAuth->prepare($query);
$patientsIds->execute();
$patientsIds = $patientsIds->fetchAll(PDO::FETCH_ASSOC);

//on recupère les praticiens
$query = "select id,email from users where role = 10";
$praticiensIds = $coAuth->prepare($query);
$praticiensIds->execute();
$praticiensIds = $praticiensIds->fetchAll(PDO::FETCH_ASSOC);

/*foreach($patientsIds as $pa) {*/
/*    // var_dump($pa['id']);*/
/*    // echo $pa['id'] . ", " . $pa['email'] ."\n";*/
/*}*/

$res = $co->exec($drop);
$res = $co->exec($cstatus);
$res = $co->exec($cpatient);
$res = $co->exec($crdv);

$faker = Faker\Factory::create('fr_FR');

// specialite
// id varchar(5),
// label varchar(50) not null,
// description text not null,
// primary key(id)



$status = [
    [ 'id' => RendezVous::MAINTENU,
        'label' => 'Maintenu'
    ],
    [
        'id' => RendezVous::PAIE,
        'label' => 'Payé'
    ],
    [
        'id' => RendezVous::HONORE,
        'label' => 'Honoré'
    ],
    [
        'id' => RendezVous::ANNULE,
        'label' => 'Annulé'
    ],
    [
        'id' => RendezVous::PAS_PAYE,
        'label' => 'Pas payé'
    ],
    [
        'id' => RendezVous::NON_HONORE,
        'label' => 'Non honoré'
    ]
];
$statusIds = [];
$query = 'insert into status (id,label) values(:id,:label);';
$insert = $co->prepare($query);
foreach($status as $s) {
    $insert->execute($s);
    $statusIds[] = $s['id'];
}




//creation des patients
// id UUID,
// nom varchar(50) not null,
// prenom varchar(50) not null,
// dateNaissance date not null,
// adresse varchar(100),
// tel varchar(20),
// mail varchar(100),
// idMedcinTraitant UUID,
// numSecuSociale varchar(50),
$query = "insert into patient (id, nom, prenom, dateNaissance, adresse, tel, mail, idMedcinTraitant, numSecuSociale) 
values (:id,:nom,:prenom,:date,:adresse,:tel, :mail, :idMedcinTraitant, :numSecuSociale);";
$insert = $co->prepare($query);
foreach($patientsIds as $pa) {
    $val = ['id' => $pa['id'],
        'nom' => $faker->lastName(),
        'prenom' => $faker->firstName(),
        'date' => $faker->date(),
        'adresse' => $faker->address(),
        'tel' => $faker->phoneNumber(),
        'mail' => $pa['email'],
        'idMedcinTraitant' => $praticiensIds[$faker->numberBetween(0, count($praticiensIds) - 1)]['id'],
        'numSecuSociale' => $faker->nir() //erreur nir pas trouvé mais fonctionne si localite du faker à fr_FR
    ];
    $insert->execute($val);
}

// id UUID,
// date timestamp,
// patientId UUID,
// praticienId UUID,
// status int,

$query = 'insert into rdv (id, date, patientId, praticienId, status) 
values(:id, :date, :patientId, :praticienId, :status);';
$insert = $co->prepare($query);
$erreurEvite = 0;
$queryVerif = 'select id from rdv where date=:date and praticienId=:praticienId and status!=:annule;';
$verif = $co->prepare($queryVerif);

for($i = 0;$i < $nbRdv;$i) {
    $val = [
        'id' => $faker->uuid(),
        'date' => $faker->dateTimeBetween('-1 days', '+10 weeks')
            ->setTime(
                $faker->numberBetween(ServiceRDV::HDEBUT[0], ServiceRDV::HFIN[0]),
                $faker->numberBetween(0, (60 % ServiceRDV::INTERVAL) - 1) * ServiceRDV::INTERVAL,
                0
            )->format('Y-m-d H:i'),
        'patientId' => $patientsIds[$faker->numberBetween(0, count($patientsIds) - 1)]['id'],
        'praticienId' => $praticiensIds[$faker->numberBetween(0, count($praticiensIds) - 1)]['id'],
        'status' => $statusIds[$faker->numberBetween(0, count($statusIds) - 1)]
    ];
    $resultVerif = 1;
    if($val['status'] != RendezVous::ANNULE) {
        // $resultVerif = $verif->execute($val);
        $verif->execute(['date' => $val['date'],'praticienId' => $val['praticienId'],'annule' => RendezVous::ANNULE]);
        $resultVerif = $verif->fetch();
    }

    if(!$resultVerif) {
        $insert->execute($val);
        $i++;
    } else {
        $erreurEvite++;
    }

}
/*echo $erreurEvite."\r\n";*/
