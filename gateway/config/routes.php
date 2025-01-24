<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteCollectorProxy;
use toubeelibgateway\application\actions\GetPraticien;
use toubeelibgateway\application\actions\GetPraticienApi;
use toubeelibgateway\application\actions\GetRdvApi;
use toubeelibgateway\application\actions\GetSpecialiteAPI;

return function (\Slim\App $app): \Slim\App {

    $app->get('/', function ($request, ResponseInterface $response, $args) {

        $response->getBody()->write("Bienvenue sur la GATEWAY de l'application Toubeelib");
        return $response->withStatus(200);
    });

    $app->group(
        '/praticiens',
        function (RouteCollectorProxy $group) {
            $group->get('{route:.*}/rdvs', GetRdvApi::class);
            $group->get('{route:.*}/dispos', GetRdvApi::class);
            $group->get('{route:.*}', GetPraticienApi::class);
        }
    );
    $app->group('/specialites', function (RouteCollectorProxy $group) {

        $group->get('{route:.*}', GetSpecialiteAPI::class);
    });

    $app->group(
        '/rdvs',
        function (RouteCollectorProxy $group) {
            $group->get('{route:.*}', GetRdvApi::class);
            $group->post('{route:.*}', GetRdvApi::class);
        }
    );

    return $app;
};
