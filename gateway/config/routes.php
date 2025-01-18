<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteCollectorProxy;
use toubeelibgateway\application\actions\GetPraticien;
use toubeelibgateway\application\actions\GetPraticienById;

return function (\Slim\App $app): \Slim\App {

    $app->get('/', function ($request, ResponseInterface $response, $args) {

        $response->getBody()->write("Hello World");
        return $response->withStatus(200);
    });

    $app->group(
        '/praticiens',
        function (RouteCollectorProxy $group) {
            $group->get('{route:.*}', GetPraticienById::class)->setName('searchPraticiens');
        }
    );

    return $app;
};
