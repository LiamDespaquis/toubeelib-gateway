<?php

namespace toubeelib\praticiens\core\services\praticien;

use DI\Container;
use toubeelib\praticiens\core\dto\InputPraticienDTO;
use toubeelib\praticiens\core\dto\PraticienDTO;
use toubeelib\praticiens\core\dto\SpecialiteDTO;

interface ServicePraticienInterface
{
    public function __construct(Container $cont);
    public function createPraticien(InputPraticienDTO $p): PraticienDTO;
    public function getPraticienById(string $id): PraticienDTO;
    public function getSpecialiteById(string $id): SpecialiteDTO;
    public function searchPraticien(PraticienDTO $pratSearch): array;
    public function getListeDisponibilite(string $id): array;
    public function getListeDisponibiliteDate(string $id, string $test_start_Date, string $test_end_Date): array;
    public function getPlanningPraticien(string $idPraticien, ?string $test_start_Date, ?string $test_end_Date): array;

}
