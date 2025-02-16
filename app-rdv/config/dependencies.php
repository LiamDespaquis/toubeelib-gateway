<?php

use GuzzleHttp\Client;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use toubeelib\rdv\core\repositoryInterfaces\AuthRepositoryInterface;
use toubeelib\rdv\core\repositoryInterfaces\PatientRepositoryInterface;
use toubeelib\rdv\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\rdv\core\repositoryInterfaces\RdvRepositoryInterface;
use toubeelib\rdv\core\services\patient\ServicePatient;
use toubeelib\rdv\core\services\patient\ServicePatientInterface;
use toubeelib\rdv\core\services\praticien\ServicePraticien;
use toubeelib\rdv\core\services\praticien\ServicePraticienInterface;
use toubeelib\rdv\core\services\rdv\AuthorizationRendezVousService;
use toubeelib\rdv\core\services\rdv\AuthorizationRendezVousServiceInterface;
use toubeelib\rdv\core\services\rdv\ServiceRDV;
use toubeelib\rdv\core\services\rdv\ServiceRDVInterface;
use toubeelib\rdv\infrastructure\notification\NotificationInfraInterface;
use toubeelib\rdv\infrastructure\notification\NotificationRabbitMq;
use toubeelib\rdv\infrastructure\repositories\ApiPraticienRepository;
use toubeelib\rdv\infrastructure\repositories\PgAuthRepository;
use toubeelib\rdv\infrastructure\repositories\PgPatientRepository;
use toubeelib\rdv\infrastructure\repositories\PgRdvRepository;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

return [

    PraticienRepositoryInterface::class => DI\autowire(ApiPraticienRepository::class),

    RdvRepositoryInterface::class => DI\autowire(PgRdvRepository::class),
    AuthRepositoryInterface::class => DI\autowire(PgAuthRepository::class),
    PatientRepositoryInterface::class => DI\autowire(PgPatientRepository::class),

    AuthorizationRendezVousServiceInterface::class => DI\autowire(AuthorizationRendezVousService::class),

    NotificationInfraInterface::class => DI\get(NotificationRabbitMq::class),


    /*NotificationRabbitMq::class => DI\create(NotificationRabbitMq::class)*/
    /*->constructor(DI\get(AMQPStreamConnection::class), DI\get('exchange.name'), DI\get('queue.name'), DI\get('routing.key')),*/

    //Services
    ServicePraticienInterface::class => DI\autowire(ServicePraticien::class),
    ServiceRDVInterface::class => DI\autowire(ServiceRDV::class),
    ServicePatientInterface::class => DI\autowire(ServicePatient::class),

    AMQPStreamConnection::class => function (ContainerInterface $c) {
        return new AMQPStreamConnection(
            $c->get('amqp.host'),
            $c->get('amqp.port'),
            $c->get('amqp.user'),
            $c->get('amqp.password')
        );
    },


    NotificationRabbitMq::class => function (ContainerInterface $c) {
        return new NotificationRabbitMq(
            $c->get(AMQPStreamConnection::class),
            $c->get('exchange.name'),
            $c->get('queue.name'),
            $c->get('routing.key')
        );
    },


    //PDO
    'pdo.commun' => function (ContainerInterface $c) {
        $config = parse_ini_file($c->get('db.config'));
        return new PDO($config['driver'].':host='.$config['host'].';port='.$config['port'].';dbname='.$config['dbname'].';user='.$config['user'].';password='.$config['password']);
    },


    StreamHandler::class => DI\create(StreamHandler::class)
        ->constructor(DI\get('logs.dir'), Logger::DEBUG)
        ->method('setFormatter', DI\get(LineFormatter::class)),


    LineFormatter::class => function () {
        $dateFormat = "Y-m-d H:i"; // Format de la date que tu veux
        $output = "[%datetime%] %channel%.%level_name%: %message% %context%\n"; // Format des logs
        return new LineFormatter($output, $dateFormat);
    },

    LoggerInterface::class => DI\create(Logger::class)->constructor('Toubeelib_logger', [DI\get(StreamHandler::class)]),
    Logger::class => DI\get(LoggerInterface::class),



    'client.praticiens' => function (ContainerInterface $c) {
        return new Client(
            /*['base_uri' => $c->get('url.gateway.praticiens')]*/
        );
    },

];
// $co = new PDO('pgsql:host=toubeelib.db;port=5432;dbname=toubeelib;user=user;password=toto');
