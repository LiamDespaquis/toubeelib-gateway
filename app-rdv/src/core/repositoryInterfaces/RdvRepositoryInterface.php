<?php

namespace toubeelib\rdv\core\repositoryInterfaces;

use DI\Container;
use SebastianBergmann\CodeCoverage\Report\Html\Renderer;
use toubeelib\rdv\core\domain\entities\praticien\Praticien;
use toubeelib\rdv\core\domain\entities\rdv\RendezVous;
use toubeelib\rdv\core\dto\InputRdvDto;

interface RdvRepositoryInterface
{
    public function __construct(Container $cont);
    public function getRdvById(string $id): RendezVous;
    public function addRdv(string $id, RendezVous $rdv): void;
    public function delete(string $id): void;
    public function cancelRdv(string $id): void;
    public function getRdvByPraticienById(string $id): array;
    public function getRdvByPraticien(Praticien $prat): array;

    public function getRdvByPatient(string $id): array;
    public function modifierRdv(RendezVous $rdv): void;

}
