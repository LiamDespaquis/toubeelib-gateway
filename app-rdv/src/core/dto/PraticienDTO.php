<?php

namespace toubeelib\rdv\core\dto;

use toubeelib\rdv\core\domain\entities\praticien\Praticien;
use toubeelib\rdv\core\dto\DTO;

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

    public function __toString(): string
    {
        return "id : $this->id,
                nom : $this->nom,
        prenom : $this->prenom,
        adresse : $this->adresse,
        tel : $this->tel,
        specialite : $this->specialiteLabel
        ";
    
    }


}
