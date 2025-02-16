<?php

use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;

use toubeelibgateway\middlewares\AuthnMiddleware;

return [

    Client::class => function (ContainerInterface $c) {
        return new Client(
            /*['base_uri' => $c->get('url.api.rdv')]*/
        );
    },

    AuthnMiddleware::class => DI\create()
    ->constructor(DI\get(Client::class), DI\get('url.api.auth')),
];
