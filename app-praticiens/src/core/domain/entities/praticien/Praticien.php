<?php

namespace toubeelib\core\domain\entities\praticien;

use toubeelib\core\domain\entities\Entity;
use toubeelib\core\dto\PraticienDTO;

class Praticien extends Entity
{
    protected string $nom;
    protected string $prenom;
    protected string $adresse;
    protected string $tel;
    protected ?Specialite $specialite = null; // version simplifiée : une seule spécialité

    public function __construct(string $nom, string $prenom, string $adresse, string $tel)
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->adresse = $adresse;
        $this->tel = $tel;
    }


    public function setSpecialite(Specialite $specialite): void
    {
        $this->specialite = $specialite;
    }

    public static function fromDTO(PraticienDTO $p): Praticien{
        $pra = new Praticien($p->nom,$p->prenom,$p->adresse,"");
        $pra->setSpecialite(new Specialite($p->id,$p->specialiteLabel));
        return ($pra);
    }

    public function toDTO(): PraticienDTO
    {
        return new PraticienDTO($this);
    }
}
