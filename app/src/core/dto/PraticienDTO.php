<?php

namespace toubeelib\core\dto;

use toubeelib\core\domain\entities\praticien\Praticien;
use toubeelib\core\dto\DTO;
use function _PHPStan_9815bbba4\React\Async\waterfall;

class PraticienDTO extends DTO
{
    public string $id;
    public string $nom;
    public string $prenom;
    public string $adresse;
    public string $tel;
    public string $specialiteLabel;

    public function __construct(Praticien $p)
    {
        $this->id = $p->getId();
        $this->nom = $p->nom;
        $this->prenom = $p->prenom;
        $this->adresse = $p->adresse;
        $this->tel = $p->tel;
        $this->specialiteLabel = $p->specialite->label;
    }

    public function jsonSerialize(): array
    {
        $retour= get_object_vars($this);
        unset($retour['businessValidator']);
        return $retour;
    }


}
