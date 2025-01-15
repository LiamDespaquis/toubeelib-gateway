<?php

namespace toubeelib\infrastructure\repositories;

use DI\Container;
use toubeelib\core\domain\entities\User;
use toubeelib\core\domain\entities\patient\Patient;
use toubeelib\core\repositoryInterfaces\PatientRepositoryInterface;
use \PDO;
use \PDOException;
use toubeelib\core\repositoryInterfaces\RepositoryInternalException;

class PgPatientRepository implements PatientRepositoryInterface{
    protected PDO $pdo;
    public function __construct(Container $co)
    {
        $this->pdo = $co->get('pdo.commun');
    }

    public function getPatientById(string $id): Patient
    {
        $query = "select * from patient where id = :id;";

        $rq = $this->pdo->prepare($query);
        try{
            $rq->execute(['id' => $id]);
            
            $pa = $rq->fetch(PDO::FETCH_ASSOC);
            
    // public function __construct(string $nom, string $prenom, string $adresse, string $tel, string $dateNaissnace, string $mail, string $idMedcinTraitant, string $numSecuSocial)
            $patient =  new Patient(
                $pa['nom'],
                $pa['prenom'],
                $pa['adresse'],
                $pa['tel'],
                $pa['datenaissance'],
                $pa['mail'],
                $pa['idmedcintraitant'],
                $pa['numsecusociale']
            );
            $patient->setId($pa['id']);
            return $patient;

        }catch(PDOException $e){
            throw new RepositoryInternalException($e);
        }
    }
}
