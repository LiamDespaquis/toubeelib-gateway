<?php

use toubeelibgateway\application\actions\GetPraticien;
use toubeelibgateway\application\actions\GetPraticienById;

return [
    GetPraticienById::class => DI\create()
        ->constructor(DI\get('client.praticiens')),
];
