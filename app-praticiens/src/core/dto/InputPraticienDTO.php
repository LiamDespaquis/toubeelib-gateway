<?php

namespace toubeelib\praticiens\core\dto;

class InputPraticienDTO extends DTO
{
    protected string $nom;
    protected string $prenom;
    protected string $adresse;
    protected string $tel;
    protected string $specialite;


    public function __construct(string $nom, string $prenom, string $adresse, string $tel, string $specialite) {
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->adresse = $adresse;
        $this->tel = $tel;
        $this->specialite = $specialite;
    }

}