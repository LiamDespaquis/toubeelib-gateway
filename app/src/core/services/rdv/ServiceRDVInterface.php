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
}
