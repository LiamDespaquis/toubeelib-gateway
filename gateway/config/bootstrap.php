<?php

use DI\ContainerBuilder;
use Slim\Factory\AppFactory;

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/settings.php');

$c = $builder->build();
$app = AppFactory::createFromContainer($c);



$app->addBodyParsingMiddleware();
// $app->addMiddleware($c->get(AuthnMiddleware::class));
$app->addRoutingMiddleware();
$app->addErrorMiddleware($c->get('displayErrorDetails'), false, false);
/*$app->addMiddleware($c->get(CorsMiddleware::class));*/



$app = (require_once __DIR__ . '/routes.php')($app);


return $app;
