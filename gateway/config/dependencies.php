<?php

use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;

return [

    Client::class => function (ContainerInterface $c) {
        return new Client(['base_uri' => $c->get('url.api')]);
    },
    'client.praticiens' => function (ContainerInterface $c) {
        return new Client(['base_uri' => $c->get('url.api.praticiens')]);
    },
];
