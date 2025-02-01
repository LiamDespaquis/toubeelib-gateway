<?php

declare(strict_types=1);

use Slim\Exception\HttpNotFoundException;



use toubeelib\application\actions\GetPatient;

use toubeelib\application\actions\GetPraticien;
use toubeelib\application\actions\GetRdvByPatient;

use toubeelib\application\actions\PostSignIn;
use toubeelib\application\actions\ValidateTokenAction;
use toubeelib\application\actions\SearchPraticien;
use toubeelib\middlewares\AuthnMiddleware;



use toubeelibgateway\application\actions\GetPraticien as toubeelibgatewayGetPraticien;

return function (\Slim\App $app): \Slim\App {

    $app->post('/signin[/]', PostSignIn::class)->setName('signIn');

    $app->get('/validateToken[/]', ValidateTokenAction::class)->setName('ValidateToken');

    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new HttpNotFoundException($request);
    });




    return $app;
};
