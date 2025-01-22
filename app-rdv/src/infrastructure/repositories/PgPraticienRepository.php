<?php

namespace toubeelib\rdv\infrastructure\repositories;

use DI\Container;
use Monolog\Logger;
use toubeelib\rdv\core\dto\PraticienDTO;
use toubeelib\rdv\core\repositoryInterfaces\RepositoryInternalException;
use toubeelib\rdv\core\domain\entities\praticien\Praticien;
use toubeelib\rdv\core\domain\entities\praticien\Specialite;
use toubeelib\rdv\core\repositoryInterfaces\PraticienRepositoryInterface;
use PDO;
use toubeelib\rdv\core\repositoryInterfaces\RepositoryEntityNotFoundException;

class PgPraticienRepository implements PraticienRepositoryInterface
{
    protected PDO $pdo;
    protected Logger $loger;

    public function __construct(Container $cont)
    {
        $this->pdo = $cont->get('pdo.commun');
        $this->loger = $cont->get(Logger::class)->withName("PgPraticienRepository");
    }

    public function getSpecialiteById(string $id): Specialite
    {
        try {
            $query = 'select * from specialite where id = :id;';
            $speSelect = $this->pdo->prepare($query);
            $speSelect->execute(['id' => $id]);
            $result = $speSelect->fetch();

            if($result) {
                return new Specialite($result['id'], $result['label'], $result['description']);
            } else {
                throw new RepositoryEntityNotFoundException("Specialite $id non trouvé");
            }

        } catch(\PDOException $e) {

            throw new RepositoryInternalException("erreur");
        }
    }

    public function save(Praticien $praticien): string
    {
    }

    public function getPraticienById(string $id): Praticien
    {
        try {
            $query = '
            select
            praticien.id as id,
            praticien.nom as nom,
            praticien.prenom as prenom,
            praticien.adresse as adresse,
            praticien.tel as tel,
            specialite.id as speid,
            specialite.label as spelabel,
            specialite.description as spedes 
            from 
            praticien,
            specialite 
            where 
            specialite.id=praticien.specialite and
            praticien.id=:id;';
            $praticienSelect = $this->pdo->prepare($query);
            $praticienSelect->execute(['id' => $id]);
            $result = $praticienSelect->fetch();
            if($result) {
                $retour = new Praticien($result['nom'], $result['prenom'], $result['adresse'], $result['tel']);
                $retour->setId($result['id']);
                $retour->setSpecialite(new Specialite($result['speid'], $result['spelabel'], $result['spedes']));
                return $retour;
            } else {
                throw new RepositoryEntityNotFoundException("Praticien $id non trouvé");
            }

        } catch(\PDOException $e) {
            throw new RepositoryInternalException('Erreure bd');
        }
    }

    public function searchPraticiens(Praticien $praticien): array
    {
        $query = "
        select 
        praticien.id as id,
        praticien.nom as nom,
        praticien.prenom as prenom,
        praticien.adresse as adresse,
        praticien.tel as tel,
        specialite.id as speid,
        specialite.label as spelabel,
        specialite.description as spedes 
        from 
        praticien,
        specialite
        where 
        praticien.specialite = specialite.id and
        upper(praticien.nom) like upper(:nom) and
        upper(praticien.prenom) like upper(:prenom) and
        upper(praticien.adresse) like upper(:adresse) and
        upper(specialite.label) like upper(:label)
        ;";
        try {
            $searchpraticiens = $this->pdo->prepare($query);
            $val = [
                'nom' => $praticien->nom,
                'prenom' => $praticien->prenom,
                'adresse' => $praticien->adresse,
                'label' => $praticien->specialite->label,
            ];
            $val = array_map(function (string $v) {
                return "%$v%";
            }, $val);
            $searchpraticiens->execute($val);
            $praticiens = $searchpraticiens->fetchAll();
            if(!$praticiens) {
                $this->loger->info("Nombre de praticien trouvé : 0");
                return [];
            }
            $retour = [];
            foreach($praticiens as $p) {
                $pra = new Praticien($p['nom'], $p['prenom'], $p['adresse'], $p['tel']);
                $pra->setId($p['id']);
                $pra->setSpecialite(new Specialite($p['speid'], $p['spelabel'], $p['spedes']));
                $retour[] = $pra;
            }
            $this->loger->info("Nombre de praticien trouvé : ". count($retour));
            return $retour;

        } catch(\PDOException $e) {
            $this->loger->error($e->getMessage());
            throw new RepositoryInternalException('erreur bd');
        }
    }
}
