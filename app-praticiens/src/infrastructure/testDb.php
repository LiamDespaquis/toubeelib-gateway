<?php
// docker exec -it toubeelib-api.toubeelib-1 php src/infrastructure/genereDB.php
$nbPraticien=50;
$nbPatient=400;
$nbRdv=240;
require_once __DIR__ .'/../../vendor/autoload.php';

use toubeelib\praticiens\core\domain\entities\rdv\RendezVous;


$co = new PDO('pgsql:host=toubeelib.db;port=5432;dbname=toubeelib;user=user;password=toto');


            $query = "select 
            rdv.id as id,rdv.patientid as patientid,rdv.praticienid as praticienid,
            rdv.date as date, praticien.specialite as specialite 
             from rdv,praticien,specialite where rdv.praticienId=praticien.id and praticien.specialite=specialite.id and praticien.id= :id;";
            $rdvs=$co->prepare($query);
            $rdvs->execute(['id'=> '6a22eaae-d6ce-37ce-8c6c-218cb7c8b488']);
            $result = $rdvs->fetchAll();

            if($result){
                $retour = [];
                foreach($result as $r){
		// var_dump($r);
		$rdv = new RendezVous($r['praticienid'],
			$r['patientid'],
			$r['specialite'],
			new DateTimeImmutable($r['date']));
                    $rdv->setId($r['id']);
                    $retour[] = $rdv;
                }
	var_dump($retour);

}
