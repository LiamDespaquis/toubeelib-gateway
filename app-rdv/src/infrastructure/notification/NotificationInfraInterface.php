<?php

namespace toubeelib\rdv\infrastructure\notification;

use toubeelib\rdv\core\domain\entities\rdv\RendezVous;

interface NotificationInfraInterface
{
    public function notifEventRdv(RendezVous $rdv, string $event): void;
}
