<?php

namespace toubeelib\rdv\core\dto;

use toubeelib\rdv\core\domain\entities\rdv\RendezVous;
use toubeelib\rdv\core\dto\PraticienDTO;
use toubeelib\rdv\core\dto\DTO;

class RdvDTO extends DTO
{
    public string $id;
    public \DateTimeImmutable $dateHeure;
    public PraticienDTO $praticien;
    public string $specialiteLabel;
    public string $patientId;
    public string $consultationType;
    public int $status;

    public function setStatus(int $status):void {
        $this->status = $status;
    }

    public function getStatus(): int{
        return $this->status;
    }


    public function __construct(RendezVous $r, PraticienDTO $praticienDTO)
    {
        $this->id = $r->getId();
        $this->dateHeure = $r->dateHeure;
        $this->praticien = $praticienDTO;
        $this->specialiteLabel = $r->specialite;
        $this->patientId = $r->patientId;
        //$this->consultationType = $r->consultationType; //todo :  0 présentiel / 1 tel consultation 
        $this->status = $r->status; //todo :  0 maintenu(default) / 1 honoré / 2 non honoré / 3 annulé /  5 payé / 6 pas payé 
    }


    public function jsonSerialize(): array
    {
        $retour= get_object_vars($this);
        unset($retour['businessValidator']);
        $retour['dateHeure']=$retour['dateHeure']->format('Y-m-d H:i');
        return $retour;
    }

}
