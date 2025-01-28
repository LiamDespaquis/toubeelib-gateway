<?php

namespace toubeelib\mailer\infra\amqp;

use Exception;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use toubeelib\mailer\infra\mailer\MailerInterface;

class Consumer
{
    private MailerInterface $mailer;
    private AMQPStreamConnection $connection;
    private string $queue;
    private AMQPChannel $channel;
    public function __construct(MailerInterface $mailer, AMQPStreamConnection $connection, string $queue)
    {
        $this->mailer = $mailer;
        $this->connection = $connection;
        $this->queue = $queue;
        $this->channel = $this->connection->channel();

    }
    public function __invoke(AMQPMessage $msg): void
    {
        $msgDecode = json_decode($msg->getBody(), true);
        $event = isset($msgDecode['event']) ? $msgDecode['event'] : "Pas d'évenement spécifié";
        $this->mailer->send(
            'info@toubeelib',
            'client@toubeelib',
            $event,
            $msg->getBody(),
        );
        $msg->getChannel()->basic_ack($msg->getDeliveryTag());
    }


    public function handle(): void
    {
        $this->channel->basic_consume($this->queue, '', false, false, false, false, $this);
        try {
            $this->channel->consume();
        } catch (Exception $e) {
            print $e->getMessage();
        }
        $this->channel->close();
        $this->connection->close();
    }

}
