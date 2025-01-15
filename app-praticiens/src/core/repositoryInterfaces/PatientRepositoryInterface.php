<?php

namespace toubeelib\core\repositoryInterfaces;

use DI\Container;
use toubeelib\core\domain\entities\patient\Patient;

interface PatientRepositoryInterface{

    public function __construct(Container $co);
    public function getPatientById(string $id): Patient;
}
