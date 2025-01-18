<?php

use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use toubeelib\rdv\core\repositoryInterfaces\AuthRepositoryInterface;
use toubeelib\rdv\core\repositoryInterfaces\PatientRepositoryInterface;
use toubeelib\rdv\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\rdv\core\repositoryInterfaces\RdvRepositoryInterface;
use toubeelib\rdv\core\services\AuthorizationPatientService;
use toubeelib\rdv\core\services\AuthorizationPatientServiceInterface;
use toubeelib\rdv\core\services\ServiceAuth;
use toubeelib\rdv\core\services\ServiceAuthInterface;
use toubeelib\rdv\core\services\patient\ServicePatient;
use toubeelib\rdv\core\services\patient\ServicePatientInterface;
use toubeelib\rdv\core\services\praticien\AuthorizationPraticienService;
use toubeelib\rdv\core\services\praticien\AuthorizationPraticienServiceInterface;
use toubeelib\rdv\core\services\praticien\ServicePraticien;
use toubeelib\rdv\core\services\praticien\ServicePraticienInterface;
use toubeelib\rdv\core\services\rdv\AuthorizationRendezVousService;
use toubeelib\rdv\core\services\rdv\AuthorizationRendezVousServiceInterface;
use toubeelib\rdv\core\services\rdv\ServiceRDV;
use toubeelib\rdv\core\services\rdv\ServiceRDVInterface;
use toubeelib\rdv\infrastructure\repositories\ApiPraticienRepository;
use toubeelib\rdv\infrastructure\repositories\PgAuthRepository;
use toubeelib\rdv\infrastructure\repositories\PgPatientRepository;
use toubeelib\rdv\infrastructure\repositories\PgPraticienRepository;
use toubeelib\rdv\infrastructure\repositories\PgRdvRepository;
use toubeelib\rdv\middlewares\AuthnMiddleware;
use toubeelib\rdv\middlewares\AuthzPatient;
use toubeelib\rdv\middlewares\AuthzPraticiens;
use toubeelib\rdv\middlewares\AuthzRDV;
use toubeelib\rdv\middlewares\CorsMiddleware;
use toubeelib\rdv\providers\auth\AuthnProviderInterface;
use toubeelib\rdv\providers\auth\JWTAuthnProvider;
use toubeelib\rdv\providers\auth\JWTManager;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

return [

    'test' => 'test',
    //Repository interface
    /*ApiPraticienRepository::class => function (ContainerInterface $c) {*/
    /*    return new ApiPraticienRepository(*/
    /*        $c->get('client.praticiens'),*/
    /*        $c->get(Logger::class),*/
    /*        uriBase: "bonjour"*/
    /*    );*/
    /*},*/
    PraticienRepositoryInterface::class => DI\autowire(ApiPraticienRepository::class),

    RdvRepositoryInterface::class => DI\autowire(PgRdvRepository::class),
    AuthRepositoryInterface::class => DI\autowire(PgAuthRepository::class),
    PatientRepositoryInterface::class => DI\autowire(PgPatientRepository::class),

    //Services
    ServicePraticienInterface::class => DI\autowire(ServicePraticien::class),
    ServiceRDVInterface::class => DI\autowire(ServiceRDV::class),
    ServiceAuthInterface::class => DI\autowire(ServiceAuth::class),
    ServicePatientInterface::class => DI\autowire(ServicePatient::class),
    AuthorizationRendezVousServiceInterface::class => DI\autowire(AuthorizationRendezVousService::class),
    AuthorizationPatientServiceInterface::class => DI\autowire(AuthorizationPatientService::class),
    AuthorizationPraticienServiceInterface::class => DI\autowire(AuthorizationPraticienService::class),




    AuthzRDV::class => DI\autowire(),
    AuthzPatient::class => DI\autowire(),
    AuthzPraticiens::class => DI\autowire(),

    //PDO
    'pdo.commun' => function (ContainerInterface $c) {
        $config = parse_ini_file($c->get('db.config'));
        return new PDO($config['driver'].':host='.$config['host'].';port='.$config['port'].';dbname='.$config['dbname'].';user='.$config['user'].';password='.$config['password']);
    },
    'pdo.auth' => function (ContainerInterface $c) {
        $config = parse_ini_file($c->get('auth.db.config'));
        return new PDO($config['driver'].':host='.$config['host'].';port='.$config['port'].';dbname='.$config['dbname'].';user='.$config['user'].';password='.$config['password']);
    },

    //auth
    JWTManager::class => DI\autowire(JWTManager::class),
    AuthnProviderInterface::class => DI\autowire(JWTAuthnProvider::class),

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


    //midleware
    AuthnMiddleware::class => DI\autowire(AuthnMiddleware::class),
    CorsMiddleware::class => DI\autowire(CorsMiddleware::class),

    'client.praticiens' => function (ContainerInterface $c) {
        return new Client(
            /*['base_uri' => $c->get('url.gateway.praticiens')]*/
        );
    },

];
// $co = new PDO('pgsql:host=toubeelib.db;port=5432;dbname=toubeelib;user=user;password=toto');
