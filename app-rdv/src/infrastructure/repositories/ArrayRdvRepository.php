<?php

namespace toubeelib\rdv\infrastructure\repositories;

use Ramsey\Uuid\Uuid;
use toubeelib\rdv\core\domain\entities\rdv\RendezVous;
use toubeelib\rdv\core\repositoryInterfaces\RdvRepositoryInterface;
use toubeelib\rdv\core\repositoryInterfaces\RepositoryEntityNotFoundException;


class ArrayRdvRepository implements RdvRepositoryInterface
{
    private array $rdvs = [];


    public function __construct() {
            $r1 = new RendezVous('p1', 'pa1', 'A', \DateTimeImmutable::createFromFormat('Y-m-d H:i','2024-09-29 09:30') );
            $r1->setId('r1');
            $r2 = new RendezVous('p1', 'pa1', 'A', \DateTimeImmutable::createFromFormat('Y-m-d H:i','2024-09-02 10:00'));
            $r2->setId('r2');
            $r3 = new RendezVous('p2', 'pa1', 'A', \DateTimeImmutable::createFromFormat('Y-m-d H:i','2024-09-02 09:30'));
            $r3->setId('r3');

        $this->rdvs  = ['r1'=> $r1, 'r2'=>$r2, 'r3'=> $r3 ];
    }

    public function getRdvById(string $id): RendezVous {
        $rdv = $this->rdvs[$id] ??
            throw new RepositoryEntityNotFoundException("RDV $id not found");

        return $rdv;
    }

    public function getRdvByPraticien(string $id) : array {
        return array_filter($this->rdvs, function($rdv) use($id) {
            return $rdv->praticienID === $id;
        });
    }

    public function addRdv(string $id, RendezVous $rdv): void {
        $this->rdvs[$id] = $rdv;
    }


    public function delete(string $id): void{
        unset($this->rdvs[$id]);
    }
    
    public function cancelRdv(string $id): void{
        $this->rdvs[$id]->setStatus(RendezVous::$ANNULE); 
    }

    public function cancelRdv(string $id, string $status): RendezVous
    {
    }


  
}
