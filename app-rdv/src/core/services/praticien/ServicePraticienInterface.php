<?php

namespace toubeelib\rdv\core\services\praticien;

use DI\Container;
use toubeelib\rdv\core\dto\InputPraticienDTO;
use toubeelib\rdv\core\dto\PraticienDTO;
use toubeelib\rdv\core\dto\SpecialiteDTO;

interface ServicePraticienInterface
{

    public function __construct(Container $cont);
    public function createPraticien(InputPraticienDTO $p): PraticienDTO;
    public function getPraticienById(string $id): PraticienDTO;
    public function getSpecialiteById(string $id): SpecialiteDTO;
    public function searchPraticien(PraticienDTO $pratSearch): array;


}
