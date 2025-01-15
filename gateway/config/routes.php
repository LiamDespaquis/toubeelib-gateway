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
            /*$group->get("/{id}[/]", GetPraticienById::class)->setName('getPraticien');*/
        }
    );

    /*$app->get('/test[/]', \toubeelib\application\actions\test::class);*/
    /**/
    /*//RENDEZVOUS*/
    /*$app->post('/rdvs[/]', \toubeelib\application\actions\PostCreateRdv::class)*/
    /*    ->setName('createRdv')*/
    /*    ->add(AuthnMiddleware::class);*/
    /*;*/
    /**/
    /*$app->get('/rdvs/{id}[/]', \toubeelib\application\actions\GetRdvId::class)*/
    /*    ->setName('getRdv')*/
    /*    ->add(AuthzRDV::class)*/
    /*    ->add(AuthnMiddleware::class);*/
    /**/
    /*$app->delete('/rdvs/{id}[/]', \toubeelib\application\actions\DeleteRdvId::class)*/
    /*    ->setName('deleteRdvId')*/
    /*    ->add(AuthzRDV::class)*/
    /*    ->add(AuthnMiddleware::class);*/
    /**/
    /*$app->patch('/rdvs/{id}[/]', \toubeelib\application\actions\PatchRdv::class)*/
    /*    ->setName('patchRdv')*/
    /*    ->add(AuthzRDV::class)*/
    /*    ->add(AuthnMiddleware::class);*/
    /**/
    /**/
    /*//PATIENTS*/
    /*$app->get('/patients/{id}/rdvs[/]', GetRdvByPatient::class)*/
    /*    ->setName('rdvPatient')*/
    /*    ->add(AuthzPatient::class)*/
    /*    ->add(AuthnMiddleware::class);*/
    /**/
    /*$app->get("/patients/{id}[/]", GetPatient::class)*/
    /*    ->setName('getPatient')*/
    /*    ->add(AuthzPatient::class)*/
    /*    ->add(AuthnMiddleware::class);*/
    /**/
    /*//PRATICIENS*/
    /**/
    /**/
    /*$app->get('/praticiens/{id}/dispos[/]', \toubeelib\application\actions\GetDisposPraticienDate::class)->setName('disposPraticienDate')*/
    /*    ->add(AuthnMiddleware::class);*/
    /**/
    /*$app->get('/praticiens/{id}/rdvs[/]', \toubeelib\application\actions\GetPraticienPlanning::class)*/
    /*    ->setName('planningPraticien')*/
    /*    ->add(AuthzPraticiens::class)*/
    /*    ->add(AuthnMiddleware::class);*/
    /**/
    /**/
    /**/
    /**/
    /*$app->post('/signin[/]', PostSignIn::class)->setName('signIn');*/
    /**/
    /**/
    /*$app->options('/{routes:.+}', function ($request, $response, $args) {*/
    /*    return $response;*/
    /*});*/
    /*$app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {*/
    /*    throw new HttpNotFoundException($request);*/
    /*});*/
    /**/
    /**/
    return $app;
};
