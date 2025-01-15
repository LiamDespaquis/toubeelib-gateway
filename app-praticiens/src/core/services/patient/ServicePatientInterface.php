<?php

namespace toubeelib\core\services\patient;

use DI\Container;
use toubeelib\core\dto\InputRdvDto;
use toubeelib\core\dto\PatientDTO;
use toubeelib\core\dto\RdvDTO;

interface ServicePatientInterface
{

    public function __construct(Container $cont);

    public function getPatientById(string $id): PatientDTO;
}
