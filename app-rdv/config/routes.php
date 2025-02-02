<?php

declare(strict_types=1);

use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteCollectorProxy;
use toubeelib\rdv\application\actions\GetDisposPraticienDate;
use toubeelib\rdv\application\actions\GetPatient;
use toubeelib\rdv\application\actions\GetPraticienPlanning;
use toubeelib\rdv\application\actions\GetRdvByPatient;
use toubeelib\rdv\application\actions\PatchRdv;
use toubeelib\rdv\application\actions\DeleteRdvId;
use toubeelib\rdv\application\actions\GetRdvId;
use toubeelib\rdv\application\actions\PostCreateRdv;
use toubeelib\rdv\middlewares\AuthnMiddleware;
use toubeelib\rdv\middlewares\AuthzRDV;

return function (\Slim\App $app): \Slim\App {


    //RENDEZVOUS
    $app->group('/rdvs', function (RouteCollectorProxy $group) {
        $group->get('[/]', function ($request, $response, $args) {
            return $response->withJson(['message' => 'Hello World']);
        })->setName('getRdvs');
        $group->post('[/]', PostCreateRdv::class)->setName('createRdv');
        $group->get('/{id}[/]', GetRdvId::class)->setName('getRdv')->add(AuthzRDV::class);
        $group->delete('/{id}[/]', DeleteRdvId::class)->setName('deleteRdvId')->add(AuthzRDV::class);
        $group->patch('/{id}[/]', PatchRdv::class)->setName('patchRdv')->add(AuthzRDV::class);
    });

    $app->get('/praticiens/{id}/rdvs[/]', GetPraticienPlanning::class)->setName('getRdvByPraticien');
    $app->get('/praticiens/{id}/dispos[/]', GetDisposPraticienDate::class)->setName('getDisposPraticienDate');
    $app->options('/{routes:.+}', function ($request, $response, $args) {
        echo $request->getMethod(). ":" . $request->getUri()->getPath();
        return $response;
    });

    $app->get('/patients/{id}/rdvs[/]', GetRdvByPatient::class)->setName('getRdvByPatient');
    $app->get('/patients/{id}[/]', GetPatient::class)->setName('getPatient');

    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new HttpNotFoundException($request);
    });


    return $app;
};
