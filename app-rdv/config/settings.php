<?php

return  [

    'displayErrorDetails' => true,
    'logs.dir' => __DIR__ . '/../var/logs',
    'db.config' => __DIR__.'/pdoConfig.ini',
    'rabbitmq.config' => __DIR__.'/rabbitmqConfig.ini',
    'auth.db.config' => __DIR__ . '/pdoConfigAuth.ini',
    'url.gateway.praticiens' => 'http://gateway.toubeelib/praticiens/',
    'exchange.name' => 'rdv.exchange',
    'queue.name' => 'rdv.queue',
    'routing.key' => 'rdv.key',


    ] ;
