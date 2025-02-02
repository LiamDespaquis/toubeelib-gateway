<?php

use GuzzleHttp\Client;
use toubeelibgateway\application\actions\GetAuthApi;
use toubeelibgateway\application\actions\GetPraticienApi;
use toubeelibgateway\application\actions\GetRdvApi;

return [
    GetPraticienApi::class => DI\create()
        ->constructor(DI\get(Client::class), DI\get('url.api.praticiens')),
    GetRdvApi::class => DI\create()
        ->constructor(DI\get(Client::class), DI\get('url.api.rdv')),
    GetAuthApi::class => DI\create()
    ->constructor(DI\get(Client::class), DI\get('url.api.auth')),

];
