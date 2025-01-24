<?php

use Psr\Container\ContainerInterface;
use toubeelib\core\repositoryInterfaces\AuthRepositoryInterface;
use toubeelib\core\repositoryInterfaces\PatientRepositoryInterface;
use toubeelib\core\repositoryInterfaces\PraticienRepositoryInterface;
use toubeelib\core\repositoryInterfaces\RdvRepositoryInterface;
use toubeelib\core\services\AuthorizationPatientService;
use toubeelib\core\services\AuthorizationPatientServiceInterface;
use toubeelib\core\services\ServiceAuth;
use toubeelib\core\services\ServiceAuthInterface;
use toubeelib\core\services\patient\ServicePatient;
use toubeelib\core\services\patient\ServicePatientInterface;
use toubeelib\core\services\praticien\AuthorizationPraticienService;
use toubeelib\core\services\praticien\AuthorizationPraticienServiceInterface;
use toubeelib\core\services\praticien\ServicePraticien;
use toubeelib\core\services\praticien\ServicePraticienInterface;
use toubeelib\core\services\rdv\AuthorizationRendezVousService;
use toubeelib\core\services\rdv\AuthorizationRendezVousServiceInterface;
use toubeelib\core\services\rdv\ServiceRDV;
use toubeelib\core\services\rdv\ServiceRDVInterface;
use toubeelib\infrastructure\repositories\PgAuthRepository;
use toubeelib\infrastructure\repositories\PgPatientRepository;
use toubeelib\infrastructure\repositories\PgPraticienRepository;
use toubeelib\infrastructure\repositories\PgRdvRepository;
use toubeelib\middlewares\AuthnMiddleware;
use toubeelib\middlewares\AuthzPatient;
use toubeelib\middlewares\AuthzPraticiens;
use toubeelib\middlewares\AuthzRDV;
use toubeelib\middlewares\CorsMiddleware;
use toubeelib\providers\auth\AuthnProviderInterface;
use toubeelib\providers\auth\JWTAuthnProvider;
use toubeelib\providers\auth\JWTManager;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;


return [

    //Repository interface
    PraticienRepositoryInterface::class => DI\autowire(PgPraticienRepository::class),
    RdvRepositoryInterface::class => DI\autowire(PgRdvRepository::class),
    AuthRepositoryInterface::class=> DI\autowire(PgAuthRepository::class),
    PatientRepositoryInterface::class => DI\autowire(PgPatientRepository::class),

    //Services
    ServicePraticienInterface::class => DI\autowire(ServicePraticien::class),
    ServiceRDVInterface::class => DI\autowire(ServiceRDV::class),
    ServiceAuthInterface::class => DI\autowire(ServiceAuth::class),
    ServicePatientInterface::class => Di\autowire(ServicePatient::class),
    AuthorizationRendezVousServiceInterface::class => DI\autowire(AuthorizationRendezVousService::class),
    AuthorizationPatientServiceInterface::class => DI\autowire(AuthorizationPatientService::class),
    AuthorizationPraticienServiceInterface::class => DI\autowire(AuthorizationPraticienService::class),


    AuthzRDV::class => DI\autowire(),
    AuthzPatient::class =>DI\autowire(),
    AuthzPraticiens::class => DI\autowire(),

    //PDO
    'pdo.commun' => function(ContainerInterface $c){
        $config= parse_ini_file($c->get('db.config'));
        return new PDO($config['driver'].':host='.$config['host'].';port='.$config['port'].';dbname='.$config['dbname'].';user='.$config['user'].';password='.$config['password']);
    },
    'pdo.auth' => function(ContainerInterface $c){
        $config = parse_ini_file($c->get('auth.db.config'));
        return new PDO($config['driver'].':host='.$config['host'].';port='.$config['port'].';dbname='.$config['dbname'].';user='.$config['user'].';password='.$config['password']);
    },

    //auth
    JWTManager::class=> DI\autowire(JWTManager::class),
    AuthnProviderInterface::class => DI\autowire(JWTAuthnProvider::class),
    
    StreamHandler::class => DI\create(StreamHandler::class)
        ->constructor(DI\get('logs.dir'), Logger::DEBUG)
        ->method('setFormatter', DI\get(LineFormatter::class)),

    
    LineFormatter::class => function() {
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
