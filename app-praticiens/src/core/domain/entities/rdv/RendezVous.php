<?php

namespace toubeelib\core\domain\entities\rdv;

use DateTimeImmutable;
use toubeelib\core\domain\entities\Entity;
use toubeelib\core\dto\InputRdvDto;
use toubeelib\core\dto\RdvDTO;
use toubeelib\core\dto\PraticienDTO;

class RendezVous extends Entity
{
    //todo :  0 maintenu(default) / 1 honoré / 2 non honoré / 3 annulé /  4 payé / 5 pas payé 
    public const  MAINTENU = 0;
    public const HONORE = 1;
    public const  NON_HONORE = 2;
    public const  ANNULE = 3;
    public const  PAIE = 4;
    public const  PAS_PAYE = 5;

    protected \DateTimeImmutable $dateHeure;
    protected string $praticienId;
    protected string $specialite;
    protected string $patientId;

    protected int $status;
    public function setStatus(int $status)
    {
        $this->status = $status;
    }


    public function getDateHeure(): \DateTimeImmutable
    {
        return $this->dateHeure;
    }

    public function getPraticienId(): string
    {
        return $this->praticienId;
    }

    public function getSpecialite(): string
    {
        return $this->specialite;
    }

    public function getPatientId(): string
    {
        return $this->patientId;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * $r1 = new RendezVous('p1', 'pa1', 'A', \DateTimeImmutable::createFromFormat('Y-m-d H:i','2024-09-02 09:00') );
     *       $r1->setID('r1');
     * @param mixed $status
     */
    public function __construct(string $praticienId, string $patientId, string $specialite, \DateTimeImmutable $dateHeure, $status = RendezVous::MAINTENU)
    {
        $this->praticienId = $praticienId;
        $this->patientId = $patientId;
        $this->dateHeure = $dateHeure;
        $this->specialite = $specialite;
        $this->status = $status;
    }

    public static function fromInputDto(InputRdvDto $rdv):RendezVous
    {
        return new RendezVous(
            $rdv->getPraticienId(),
            $rdv->getPatientId(),
            $rdv->getSpecialite(),
            $rdv->getDateHeure());
    }

    public function toDTO(PraticienDTO $praticienDTO): RdvDTO
    {
        return new RdvDTO($this, $praticienDTO);
    }
}
