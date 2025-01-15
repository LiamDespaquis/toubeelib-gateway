<?php

namespace toubeelib\core\services\rdv;

use DI\Container;
use toubeelib\core\dto\InputRdvDto;
use toubeelib\core\dto\RdvDTO;

interface ServiceRDVInterface
{

    public function __construct(Container $cont);
    public function getRdvById(string $id): RdvDTO;
        public function creerRendezvous(InputRdvDto $rdv): RdvDTO;
        public function modifRendezVous(InputRdvDto $rdv): RdvDTO;
    public function getListeDisponibilite(string $id): array;
    public function getListeDisponibiliteDate(string $id, string $test_start_Date, string $test_end_Date): array;
    public function getRdvByPatient(string $id):array;

    public function getPlanningPraticien(string $idPraticien, ?string $test_start_Date, ?string $test_end_Date): array;
}
