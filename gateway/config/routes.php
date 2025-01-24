<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteCollectorProxy;
use toubeelibgateway\application\actions\GetPraticien;
use toubeelibgateway\application\actions\GetPraticienApi;
use toubeelibgateway\application\actions\GetRdv;
use toubeelibgateway\application\actions\GetSpecialiteAPI;

return function (\Slim\App $app): \Slim\App {

    $app->get('/', function ($request, ResponseInterface $response, $args) {

        $response->getBody()->write("Bienvenue sur la GATEWAY de l'application Toubeelib");
        return $response->withStatus(200);
    });

    $app->group(
        '/praticiens',
        function (RouteCollectorProxy $group) {
            $group->get('{route:.*}', GetPraticienApi::class);
        }
    );
    $app->group('/specialites', function (RouteCollectorProxy $group) {

        $group->get('{route:.*}', GetSpecialiteAPI::class);
    });

    $app->group(
        '/rdvs',
        function (RouteCollectorProxy $group) {
            $group->get('{route:.*}', GetRdv::class);
            $group->post('{route:.*}', GetRdv::class);
        }
    );

    $app->group(
        '/auth', 
        function (RouteCollectorProxy $group) {
            $group->get('{route:.*}', GetAuthAPI::class);
            $group->post('{route:.*}', GetAuthApi::class);
        }
    );

    return $app;
};
