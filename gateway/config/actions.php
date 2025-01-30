<?php

use GuzzleHttp\Client;
use toubeelibgateway\application\actions\GetAuthApi;
use toubeelibgateway\application\actions\GetPraticien;
use toubeelibgateway\application\actions\GetPraticienApi;
use toubeelibgateway\application\actions\GetRdvApi;
use toubeelibgateway\application\actions\GetSpecialiteAPI;

return [
    GetPraticienApi::class => DI\create()
        ->constructor(DI\get('client.praticiens'), DI\get('url.api.praticiens')),
    GetRdvApi::class => DI\create()
        ->constructor(DI\get('client.rdvs'), DI\get('url.api.rdv')),
    GetSpecialiteAPI::class => DI\create()
        ->constructor(DI\get('client.praticiens'), DI\get('url.api.praticiens')),
    GetAuthApi::class => DI\create()
    ->constructor(DI\get(Client::class), DI\get('url.api.auth')),

];
