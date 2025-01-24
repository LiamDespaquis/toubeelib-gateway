<?php

use PhpAmqpLib\Message\AMQPMessage;

use PhpAmqpLib\Connection\AMQPStreamConnection;

require_once __DIR__ . '/../vendor/autoload.php';
$queue = 'test.queue';
$connection = new AMQPStreamConnection('queue', 5672, 'user', 'toto');
$channel = $connection->channel();
$callback = function (AMQPMessage $msg) {
    $msg_body = json_decode($msg->getBody(), true);
    print "[x] message reçu : \n";
    $msg->getChannel()->basic_ack($msg->getDeliveryTag());
};
$channel->basic_consume($queue, '', false, false, false, false, $callback);
try {
    $channel->consume();
} catch (Exception $e) {
    print $e->getMessage();
}
$channel->close();
$connection->close();
