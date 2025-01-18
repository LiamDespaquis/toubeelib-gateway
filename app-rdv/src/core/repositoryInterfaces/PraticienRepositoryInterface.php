<?php

namespace toubeelib\rdv\core\repositoryInterfaces;

use DI\Container;
use toubeelib\rdv\core\domain\entities\praticien\Praticien;
use toubeelib\rdv\core\domain\entities\praticien\Specialite;

interface PraticienRepositoryInterface
{
    public function getSpecialiteById(string $id): Specialite;
    public function save(Praticien $praticien): string;
    public function getPraticienById(string $id): Praticien;
    public function searchPraticiens(Praticien $praticien): array;

}
