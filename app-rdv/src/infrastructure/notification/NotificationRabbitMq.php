<?php

namespace toubeelib\rdv\infrastructure\notification;

use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use toubeelib\rdv\core\domain\entities\rdv\RendezVous;
use toubeelib\rdv\infrastructure\notification\NotificationInfraInterface;

class NotificationRabbitMq implements NotificationInfraInterface
{
    protected AMQPStreamConnection $connection;
    protected string $exchangeName;
    protected string $queueName;
    protected string $routingKey;
    public function __construct(AMQPStreamConnection $connection, string $exchangeName, string $queueName, string $routingKey)
    {
        $this->connection = $connection;
        $this->exchangeName = $exchangeName;
        $this->queueName = $queueName;
        $this->routingKey = $routingKey;
    }


    public function notifEventRdv(RendezVous $rdv, string $event): void
    {

        $channel = $this->connection->channel();
        $channel->exchange_declare($this->exchangeName, 'direct', false, true, false);
        $channel->queue_declare($this->queueName, false, true, false, false);
        $channel->queue_bind($this->queueName, $this->exchangeName, $this->routingKey);


        $msg = new AMQPMessage(json_encode($rdv));
        $channel->basic_publish($msg, $this->exchangeName, $this->routingKey);

        $channel->close();
    }
}
