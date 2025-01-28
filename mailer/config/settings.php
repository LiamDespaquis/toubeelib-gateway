<?php

use Psr\Container\ContainerInterface;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use toubeelib\mailer\infra\amqp\Consumer;
use toubeelib\mailer\infra\mailer\MailerInterface;
use toubeelib\mailer\infra\mailer\MailerSymfony;

return [

    //amqp config
    'amqp.queue' => getenv('QUEUE'),
    'amqp.exchange' => getenv('EXCHANGE'),

    'amqp.host' => getenv('HOST'),
    'amqp.user' => getenv('USER'),
    'amqp.password' => getenv('PASSWORD'),
    'amqp.port' => getenv('PORT'),

    AMQPStreamConnection::class => function (ContainerInterface $c) {
        return new AMQPStreamConnection(
            $c->get('amqp.host'),
            $c->get('amqp.port'),
            $c->get('amqp.user'),
            $c->get('amqp.password')
        );
    },

    //dsn config

    'dsn' => getenv('DSN'),

    //dependences
    MailerInterface::class => DI\get(MailerSymfony::class),

    MailerSymfony::class => DI\create(MailerSymfony::class)
        ->constructor(DI\get('dsn')),

    //amqp consumer
    Consumer::class => DI\create(Consumer::class)
        ->constructor(DI\get(MailerInterface::class), DI\get(AMQPStreamConnection::class), DI\get('amqp.queue')),
];
