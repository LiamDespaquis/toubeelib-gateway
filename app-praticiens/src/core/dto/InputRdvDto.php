<?php

namespace toubeelib\core\dto;

use DateTimeImmutable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use toubeelib\application\actions\AbstractAction;
use toubeelib\core\services\rdv\ServiceRDVInvalidDataException;
use function PHPUnit\Framework\isFalse;

class InputRdvDto extends DTO
{
    protected string $praticienId, $specialite, $patientId, $id;
    protected \DateTimeImmutable $dateHeure;

    public function setId(string $id):void{
        $this->id=$id;
    }
    public function getId():string {
        return $this->id;
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

    public function getDateHeure(): \DateTimeImmutable
    {
        return $this->dateHeure;
    }

    /**
     * @param string $praticienId
     * @param string $specialite
     * @param string $patientId
     * @param \DateTimeImmutable $dateHeure
     */
    public function __construct(string $praticienId, string $patientId, string $specialite, string $dateHeure)
    {
        $this->praticienId = $praticienId;
        $this->patientId = $patientId;
        $this->specialite = $specialite;
        $this->dateHeure = \DateTimeImmutable::createFromFormat('Y-m-d H:i', $dateHeure );
        if($this->dateHeure == false){
            throw new ServiceRDVInvalidDataException('format de date invalide');
        }
    }
    /**
     * @param array<int,mixed> $rdv
     * inputRdvDto depuis array avec praticienId, patientId, specialite, dateHeure
     */
    public static function fromArray(array $rdv): InputRdvDto{
        return new InputRdvDto($rdv['praticienId'], $rdv['patientId'], $rdv['specialite'], $rdv['dateHeure']);
    }



}
