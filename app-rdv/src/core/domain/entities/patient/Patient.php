<?php

namespace toubeelib\rdv\core\domain\entities\patient;

use toubeelib\rdv\core\domain\entities\Entity;
use toubeelib\rdv\core\dto\PatientDTO;

class Patient extends Entity
{
    protected string $nom,
    $prenom,
    $adresse,
    $tel,
    $dateNaissance, $mail, $idMedcinTraitant, $numSecuSocial;

    public function __construct(string $nom, string $prenom, string $adresse, string $tel, string $dateNaissnace, string $mail, string $idMedcinTraitant, string $numSecuSocial)
    {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->adresse = $adresse;
        $this->tel = $tel;
        $this->dateNaissance = $dateNaissnace;
        $this->mail = $mail;
        $this->idMedcinTraitant = $idMedcinTraitant;
        $this->numSecuSocial = $numSecuSocial;
    }

    public function toDTO(): PatientDTO
    {
        return new PatientDTO($this);
    }
}
