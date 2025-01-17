<?php

namespace toubeelib\praticiens\core\services\patient;

use DI\Container;
use toubeelib\praticiens\core\dto\PatientDTO;

interface ServicePatientInterface
{

    public function __construct(Container $cont);

    public function getPatientById(string $id): PatientDTO;
}
