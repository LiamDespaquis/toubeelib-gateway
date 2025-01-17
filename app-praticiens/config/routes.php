<?php

declare(strict_types=1);

use Slim\Exception\HttpNotFoundException;
use toubeelib\praticiens\application\actions\GetDisposPraticienDate;
use toubeelib\praticiens\application\actions\GetPraticien;
use toubeelib\praticiens\application\actions\GetPraticienPlanning;
use toubeelib\praticiens\application\actions\HomeAction;
use toubeelib\praticiens\application\actions\SearchPraticien;
use toubeelib\praticiens\middlewares\AuthnMiddleware;
use toubeelib\praticiens\middlewares\AuthzPraticiens;

return function (\Slim\App $app): \Slim\App {

    $app->get('/', HomeAction::class);
    //PRATICIENS

    $app->get('/praticiens/{id}/dispos[/]', GetDisposPraticienDate::class)
        ->setName('disposPraticienDate')
        ->add(AuthnMiddleware::class);

    $app->get('/praticiens/{id}/rdvs[/]', GetPraticienPlanning::class)
        ->setName('planningPraticien')
        ->add(AuthzPraticiens::class)
        ->add(AuthnMiddleware::class);

    $app->get('/praticiens[/]', SearchPraticien::class)->setName('searchPraticiens')
        ->add(AuthnMiddleware::class);

    $app->get("/praticiens/{id}[/]", GetPraticien::class)->setName('getPraticien')
        ->add(AuthnMiddleware::class);

    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new HttpNotFoundException($request);
    });


    return $app;
};
