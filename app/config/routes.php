<?php

declare(strict_types=1);

use Slim\Exception\HttpNotFoundException;



use toubeelib\application\actions\GetPatient;

use toubeelib\application\actions\GetPraticien;
use toubeelib\application\actions\GetRdvByPatient;

use toubeelib\application\actions\PostSignIn;
use toubeelib\application\actions\SearchPraticien;
use toubeelib\middlewares\AuthnMiddleware;



use toubeelibgateway\application\actions\GetPraticien as toubeelibgatewayGetPraticien;

return function (\Slim\App $app): \Slim\App {

    $app->get('/', \toubeelib\application\actions\HomeAction::class);

    $app->get('/test[/]', \toubeelib\application\actions\test::class);

    //RENDEZVOUS
    $app->post('/rdvs[/]', \toubeelib\application\actions\PostCreateRdv::class)
        ->setName('createRdv')
        ->add(AuthnMiddleware::class);
    ;

    $app->get('/rdvs/{id}[/]', \toubeelib\application\actions\GetRdvId::class)
        ->setName('getRdv')
        ->add(AuthnMiddleware::class);

    $app->delete('/rdvs/{id}[/]', \toubeelib\application\actions\DeleteRdvId::class)
        ->setName('deleteRdvId')
        ->add(AuthnMiddleware::class);

    $app->patch('/rdvs/{id}[/]', \toubeelib\application\actions\PatchRdv::class)
        ->setName('patchRdv')
        ->add(AuthnMiddleware::class);


    //PATIENTS
    $app->get('/patients/{id}/rdvs[/]', GetRdvByPatient::class)
        ->setName('rdvPatient');

    $app->get("/patients/{id}[/]", GetPatient::class)
        ->setName('getPatient');

    //PRATICIENS


    $app->get('/praticiens/{id}/dispos[/]', \toubeelib\application\actions\GetDisposPraticienDate::class)->setName('disposPraticienDate')
        ->add(AuthnMiddleware::class);

    $app->get('/praticiens/{id}/rdvs[/]', \toubeelib\application\actions\GetPraticienPlanning::class)
        ->setName('planningPraticien')
        /*->add(AuthzPraticiens::class)*/
        ->add(AuthnMiddleware::class);

    $app->get('/praticiens[/]', SearchPraticien::class)->setName('searchPraticiens')
        ->add(AuthnMiddleware::class);

    $app->get("/praticiens/{id}[/]", GetPraticien::class)->setName('getPraticien')
        ->add(AuthnMiddleware::class);


    $app->post('/signin[/]', PostSignIn::class)->setName('signIn');


    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new HttpNotFoundException($request);
    });


    return $app;
};
