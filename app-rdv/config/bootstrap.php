<?php

use DI\ContainerBuilder;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Factory\AppFactory;
use Slim\Middleware\ErrorMiddleware;
use toubeelib\rdv\middlewares\AuthnMiddleware;
use toubeelib\rdv\middlewares\CorsMiddleware;

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/constantes.php');
$builder->addDefinitions(__DIR__ . '/settings.php');
$builder->addDefinitions(__DIR__ . '/dependencies.php');
$builder->addDefinitions(__DIR__ . '/actions.php');

$c = $builder->build();

$app = AppFactory::createFromContainer($c);


$app->addBodyParsingMiddleware();
$app->addRoutingMiddleware();
$app->addErrorMiddleware($c->get('displayErrorDetails'), false, false);



$app = (require_once __DIR__ . '/routes.php')($app);
// $routeParser = $app->getRouteCollector()->getRouteParser();


return $app;
