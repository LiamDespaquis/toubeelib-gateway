<?php

return  [

    'displayErrorDetails' => true,
    'logs.dir' => __DIR__ . '/../var/logs',
    'db.config' => __DIR__.'/pdoConfig.ini',
    'auth.db.config' => __DIR__ . '/pdoConfigAuth.ini',
    'url.gateway.praticiens' => 'http://gateway.toubeelib/praticiens/',

    //amqp config queue
    'exchange.name' => getenv('EXCHANGE'),
    'queue.name' => getenv('QUEUE'),
    'routing.key' => getenv('ROUTING_KEY'),

    //amqp config auth
    'amqp.host' => getenv('HOST'),
    'amqp.user' => getenv('USER'),
    'amqp.password' => getenv('PASSWORD'),
    'amqp.port' => getenv('PORT'),



    ] ;
