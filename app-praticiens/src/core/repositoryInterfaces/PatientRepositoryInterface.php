<?php

namespace toubeelib\praticiens\core\repositoryInterfaces;

use DI\Container;
use toubeelib\praticiens\core\domain\entities\patient\Patient;

interface PatientRepositoryInterface{

    public function __construct(Container $co);
    public function getPatientById(string $id): Patient;
}
