<?php

namespace toubeelib\rdv\middlewares;

use DI\Container;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Routing\RouteContext;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use toubeelib\rdv\core\services\ServiceRessourceNotFoundException;
use toubeelib\rdv\providers\auth\AuthInvalidException;
use toubeelib\rdv\core\services\rdv\AuthorizationRendezVousServiceInterface;

class AuthzRDV implements MiddlewareInterface
{
    protected Logger $loger;
    protected AuthorizationRendezVousServiceInterface $authrdvservice;

    protected string $key;
    protected string $algo;


    public function __construct(Container $co)
    {
        $this->loger = $co->get(Logger::class)->withName("AutnzRDVMiddleware");
        $this->authrdvservice = $co->get(AuthorizationRendezVousServiceInterface::class);
        //Pour pouvoir manipuler le token
        $this->key = getenv('JWT_SECRET_KEY');
        $this->algo = $co->get('token.jwt.algo');
    }

    public function process(ServerRequestInterface $rq, RequestHandlerInterface $next): ResponseInterface
    {
        //Lignes pour récupérer le token et le décoder -->
        $idRdv = RouteContext::fromRequest($rq)->getRoute()->getArgument('id');

        $token = $rq->getHeader("Authorization")[0];
        $token = sscanf($token, "Bearer %s")[0];

        [, $payload_b64] = explode('.', $token);
        $payload = JWT::jsonDecode(JWT::urlsafeB64Decode($payload_b64));
        try {
            if($this->authrdvservice->isGranted($payload->sub, 1, $idRdv, $payload->role)) {
                return $next->handle($rq);
            } else {
                throw new HttpUnauthorizedException($rq, "Accès au rdv $idRdv non authorisé");
            }
        } catch(ServiceRessourceNotFoundException $e) {
            throw new HttpNotFoundException($rq, $e->getMessage());
        }


        $rs = $next->handle($rq);
        //après requete
        return $rs;
    }

}
