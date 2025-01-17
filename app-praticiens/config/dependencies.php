<?php

use Psr\Container\ContainerInterface;
use toubeelib\praticiens\core\repositoryInterfaces\AuthRepositoryInterface;
use toubeelib\praticiens\core\repositoryInterfaces\PatientRepositoryInterface;
use toubeelib\praticiens\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\praticiens\core\repositoryInterfaces\RdvRepositoryInterface;
use toubeelib\praticiens\core\services\AuthorizationPatientService;
use toubeelib\praticiens\core\services\AuthorizationPatientServiceInterface;
use toubeelib\praticiens\core\services\ServiceAuth;
use toubeelib\praticiens\core\services\ServiceAuthInterface;
use toubeelib\praticiens\core\services\patient\ServicePatient;
use toubeelib\praticiens\core\services\patient\ServicePatientInterface;
use toubeelib\praticiens\core\services\praticien\AuthorizationPraticienService;
use toubeelib\praticiens\core\services\praticien\AuthorizationPraticienServiceInterface;
use toubeelib\praticiens\core\services\praticien\ServicePraticien;
use toubeelib\praticiens\core\services\praticien\ServicePraticienInterface;
use toubeelib\praticiens\core\services\rdv\AuthorizationRendezVousService;
use toubeelib\praticiens\core\services\rdv\AuthorizationRendezVousServiceInterface;
use toubeelib\praticiens\core\services\rdv\ServiceRDV;
use toubeelib\praticiens\core\services\rdv\ServiceRDVInterface;
use toubeelib\praticiens\infrastructure\repositories\PgAuthRepository;
use toubeelib\praticiens\infrastructure\repositories\PgPatientRepository;
use toubeelib\praticiens\infrastructure\repositories\PgPraticienRepository;
use toubeelib\praticiens\infrastructure\repositories\PgRdvRepository;
use toubeelib\praticiens\middlewares\AuthnMiddleware;
use toubeelib\praticiens\middlewares\AuthzPatient;
use toubeelib\praticiens\middlewares\AuthzPraticiens;
use toubeelib\praticiens\middlewares\AuthzRDV;
use toubeelib\praticiens\middlewares\CorsMiddleware;
use toubeelib\praticiens\providers\auth\AuthnProviderInterface;
use toubeelib\praticiens\providers\auth\JWTAuthnProvider;
use toubeelib\praticiens\providers\auth\JWTManager;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

return [

    //Repository interface
    PraticienRepositoryInterface::class => DI\autowire(PgPraticienRepository::class),
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

    Logger::class => DI\create(Logger::class)->constructor('Toubeelib_logger', [DI\get(StreamHandler::class)]),


    //midleware
    AuthnMiddleware::class => DI\autowire(AuthnMiddleware::class),
    CorsMiddleware::class => DI\autowire(CorsMiddleware::class),


];
// $co = new PDO('pgsql:host=toubeelib.db;port=5432;dbname=toubeelib;user=user;password=toto');
