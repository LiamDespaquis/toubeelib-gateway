<?php


use toubeelib\mailer\infra\amqp\Consumer;
use DI\ContainerBuilder;

require_once __DIR__ . '/vendor/autoload.php';


$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__ . '/config/settings.php');

$c = $builder->build();
$consumer = $c->get(Consumer::class);

$consumer->handle();
