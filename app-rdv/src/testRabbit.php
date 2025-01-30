<?php

require_once __DIR__ . '/../vendor/autoload.php';
use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;

$exchange_name = 'test.exchange';
$queue_name = 'test.queue';
$routing_key = 'test.key';
$connection = new AMQPStreamConnection('queue', 5672, 'user', 'toto');
$channel = $connection->channel();
$channel->exchange_declare($exchange_name, 'direct', false, true, false);
$channel->queue_declare($queue_name, false, true, false, false);
$channel->queue_bind($queue_name, $exchange_name, $routing_key);

$msgBody = [
    'praticienId' => '1',
    'patientId' => '2',
    'specialite' => 'dentiste',
    'dateHeure' => '2021-12-12 12:00:00'
];
$msg = new AMQPMessage(json_encode($msgBody));
$channel->basic_publish($msg, $exchange_name, $routing_key);

$channel->close();
$connection->close();
