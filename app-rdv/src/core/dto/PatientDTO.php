<?php

namespace toubeelib\rdv\core\dto;

use toubeelib\rdv\core\domain\entities\patient\Patient;
use toubeelib\rdv\core\dto\DTO;

class PatientDTO extends DTO
{
    
    protected string $ID;
    protected string $nom;
    protected string $prenom;
    protected string $adresse;
    protected string $tel,
    $dateNaissance, $mail, $idMedcinTraitant, $numSecuSocial;

    public function __construct(Patient $p)
    {
        $this->ID = $p->getId();
        $this->nom = $p->nom;
        $this->prenom = $p->prenom;
        $this->adresse = $p->adresse;
        $this->tel = $p->tel;
        $this->dateNaissance = $p->dateNaissance;
        $this->mail = $p->mail;
        $this->idMedcinTraitant = $p->idMedcinTraitant;
        $this->numSecuSocial = $p->numSecuSocial;

    }
}
