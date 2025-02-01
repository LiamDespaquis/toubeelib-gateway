<?php

namespace toubeelib\rdv\infrastructure\repositories;

use DI\Container;
use Monolog\Logger;
use PDO;
use toubeelib\rdv\core\domain\entities\praticien\Praticien;
use toubeelib\rdv\core\domain\entities\rdv\RendezVous;
use toubeelib\rdv\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\rdv\core\repositoryInterfaces\RdvRepositoryInterface;
use toubeelib\rdv\core\repositoryInterfaces\RepositoryEntityNotFoundException;
use toubeelib\rdv\core\repositoryInterfaces\RepositoryInternalException;

class PgRdvRepository implements RdvRepositoryInterface
{
    protected PDO $pdo;
    protected Logger $loger;
    protected PraticienRepositoryInterface $repositoryPraticien;

    public function __construct(Container $cont)
    {
        $this->pdo = $cont->get('pdo.commun');
        $this->loger = $cont->get(Logger::class)->withName('PgRdvRepository');
        $this->repositoryPraticien = $cont->get(PraticienRepositoryInterface::class);

    }

    // id UUID,
    // date timestamp,
    // patientId UUID,
    // praticienId UUID,
    // status int,
    // primary key(id),
    public function getRdvById(string $id): RendezVous
    {
        try {
            $query = 'select
            rdv.id as id,
            rdv.patientid as patientid,
            rdv.praticienid as praticienid,
            to_char(rdv.date,\'YYYY-MM-DD HH24:MI\') as date, 
            rdv.status as status
            from rdv
            where 
            rdv.id=:id;';
            $statement = $this->pdo->prepare($query);
            $statement->execute(['id' => $id]);
            $rdv = $statement->fetch();

            if($rdv) {
                $retour = new RendezVous(
                    $rdv['praticienid'],
                    $rdv['patientid'],
                    '',
                    \DateTimeImmutable::createFromFormat('Y-m-d H:i', $rdv['date']),
                    $rdv['status']
                );
                $retour->setId($rdv['id']);
                return $retour;
            } else {
                throw new RepositoryEntityNotFoundException("Rendez vous $id n'existe pas");
            }
        } catch(\PDOException $e) {

            // throw new RepositoryInternalException('Problème avec la base de donnée postgres');
            throw new RepositoryInternalException($e->getMessage());
        }
    }

    public function addRdv(string $id, RendezVous $rdv): void
    {
        try {
            $query = 'insert into rdv (id, date, patientId, praticienId, status) 
            values(:id, :date, :patientId, :praticienId, :status);';
            $val = [
                'id' => $id,
                'date' => $rdv->getDateHeure()->format('Y-m-d H:i'),
                'patientId' => $rdv->getPatientID(),
                'praticienId' => $rdv->getPraticienID(),
                'status' => $rdv->getStatus()
            ];
            $this->pdo->prepare($query)->execute($val);
        } catch(\PDOException $e) {
            throw new RepositoryInternalException($e->getMessage());
        }
    }

    public function delete(string $id): void
    {
        try {
            $query = 'delete from rdv where id=:id;';
            $val = [
                'id' => $id
            ];

            $this->pdo->prepare($query)->execute($val);

        } catch(\PDOException $e) {
            throw new RepositoryInternalException($e->getMessage());
        }
    }

    public function getRdvByPraticienById(string $id): array
    {
        $praticien = $this->repositoryPraticien->getPraticienById($id);
        return $this->getRdvByPraticien($praticien);

    }

    public function getRdvByPatient(string $id): array
    {
        try {
            $query = "
            select 
                rdv.id as id,
                rdv.patientid as patientid,
                rdv.praticienid as praticienid,
                rdv.date as date, 
                rdv.status as status,
                praticien.specialite as specialite 
            from 
                rdv,
                praticien,
                specialite 
            where 
                rdv.praticienId=praticien.id 
                and praticien.specialite=specialite.id 
                and rdv.patientid= :id;";
            $rdvs = $this->pdo->prepare($query);
            $rdvs->execute(['id' => $id]);
            $result = $rdvs->fetchAll();

            if($result) {
                $retour = [];
                foreach($result as $r) {
                    $rdv = new RendezVous($r['praticienid'], $r['patientid'], $r['specialite'], new \DateTimeImmutable($r['date']), $r['status']);
                    $rdv->setId($r['id']);
                    $retour[] = $rdv;
                }
                return $retour;


            } else {
                throw new RepositoryEntityNotFoundException("Patient $id not found");
            }

        } catch(\PDOException $e) {
            throw new RepositoryInternalException($e->getMessage());
        } catch(\Exception $e) {
            throw new RepositoryInternalException($e->getMessage());
        }
    }

    public function cancelRdv(string $id): void
    {
        try {
            $query = 'update rdv
            set status= :annule
            where id = :id;';
            $val = [
                'annule' => RendezVous::ANNULE,
                'id' => $id
            ];
            $rdvAffecte = $this->pdo->prepare($query)->execute($val);
            if(!$rdvAffecte) {
                throw new RepositoryEntityNotFoundException("Rdv $id non trouvé, rdv affécté = $rdvAffecte");
            }

        } catch(\PDOException $e) {
            throw new RepositoryInternalException($e->getMessage());
        }
    }

    public function modifierRdv(RendezVous $rdv): void
    {
        try {
            $query =
            'update rdv
            set date = :date,
            patientId = :patientId,
            praticienId = :praticienId,
            status = :status
            where id = :id;';
            $val = [
                'id' => $rdv->getId(),
                'date' => $rdv->getDateHeure()->format('Y-m-d H:i'),
                'patientId' => $rdv->getPatientID(),
                'praticienId' => $rdv->getPraticienID(),
                'status' => $rdv->getStatus()
            ];
            $this->pdo->prepare($query)->execute($val);

        } catch(\PDOException $e) {
            $this->loger->error($e->getMessage());
            throw new RepositoryInternalException($e->getMessage());
        }
    }

    public function getRdvByPraticien(Praticien $prat): array
    {
        try {
            $id = $prat->getId();
            $query = "select 
            rdv.id as id,
            rdv.patientid as patientid,
            rdv.praticienid as praticienid,
            rdv.date as date
            from rdv
            where 
            rdv.praticienId= :id;";
            $rdvs = $this->pdo->prepare($query);
            $rdvs->execute(['id' => $id]);
            $result = $rdvs->fetchAll();


            if($result) {
                $retour = [];
                foreach($result as $r) {
                    $rdv = new RendezVous($r['praticienid'], $r['patientid'], $prat->specialite->label, new \DateTimeImmutable($r['date']));
                    $rdv->setId($r['id']);
                    $retour[] = $rdv;
                }
                return $retour;


            } else {
                $this->loger->error("");
                throw new RepositoryEntityNotFoundException("Praticien $id not found");
            }

        } catch(\PDOException $e) {
            throw new RepositoryInternalException($e->getMessage());
        } catch(\Exception $e) {
            throw new RepositoryInternalException($e->getMessage());
        }
    }

}
