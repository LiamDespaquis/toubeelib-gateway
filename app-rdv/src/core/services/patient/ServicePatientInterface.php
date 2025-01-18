<?php

namespace toubeelib\rdv\core\services\patient;

use DI\Container;
use toubeelib\rdv\core\dto\InputRdvDto;
use toubeelib\rdv\core\dto\PatientDTO;
use toubeelib\rdv\core\dto\RdvDTO;

interface ServicePatientInterface
{

    public function __construct(Container $cont);

    public function getPatientById(string $id): PatientDTO;
}
