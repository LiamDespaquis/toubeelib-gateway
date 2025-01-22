<?php

use toubeelibgateway\application\actions\GetPraticien;
use toubeelibgateway\application\actions\GetPraticienById;
use toubeelibgateway\application\actions\GetRdv;

return [
    GetPraticienById::class => DI\create()
        ->constructor(DI\get('client.praticiens'), DI\get('url.api.praticiens')),
    GetRdv::class => DI\create()
        ->constructor(DI\get('client.rdvs'), DI\get('url.api.rdv')),
];
