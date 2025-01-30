<?php

namespace toubeelib\rdv\infrastructure\notification;

use toubeelib\rdv\core\domain\entities\rdv\RendezVous;
use toubeelib\rdv\core\dto\RdvDTO;

interface NotificationInfraInterface
{
    public function notifEventRdv(RdvDTO $rdv, string $event): void;
}
