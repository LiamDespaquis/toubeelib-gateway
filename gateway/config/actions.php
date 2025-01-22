<?php

use toubeelibgateway\application\actions\GetPraticien;
use toubeelibgateway\application\actions\GetPraticienApi;
use toubeelibgateway\application\actions\GetRdv;
use toubeelibgateway\application\actions\GetSpecialiteAPI;

return [
    GetPraticienApi::class => DI\create()
        ->constructor(DI\get('client.praticiens'), DI\get('url.api.praticiens')),
    GetRdv::class => DI\create()
        ->constructor(DI\get('client.rdvs'), DI\get('url.api.rdv')),
    GetSpecialiteAPI::class => DI\create()
        ->constructor(DI\get('client.praticiens'), DI\get('url.api.praticiens')),

];
