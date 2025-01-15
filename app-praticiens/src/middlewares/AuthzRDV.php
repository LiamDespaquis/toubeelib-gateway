<?php
namespace toubeelib\middlewares;

use DI\Container;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Routing\RouteContext;
use toubeelib\core\services\ServiceRessourceNotFoundException;
use toubeelib\providers\auth\AuthInvalidException;
use toubeelib\core\services\rdv\AuthorizationRendezVousServiceInterface;

class AuthzRDV implements MiddlewareInterface{

	protected Logger $loger;
	protected AuthorizationRendezVousServiceInterface $authrdvservice;

	
	public function __construct(Container $co)
	{
		$this->loger = $co->get(Logger::class)->withName("AutnzRDVMiddleware");
		$this->authrdvservice = $co->get(AuthorizationRendezVousServiceInterface::class);
	}

	public function process(ServerRequestInterface $rq, RequestHandlerInterface $next): ResponseInterface
	{
		$idRdv = RouteContext::fromRequest($rq)->getRoute()->getArgument('id');
		$user = $rq->getAttribute('user');
		try{
		if( $this->authrdvservice->isGranted($user->id, 1, $idRdv, $user->role)){
			return $next->handle($rq);
		}else{
			throw new HttpUnauthorizedException($rq, "Accès au rdv $idRdv non authorisé");
		}
		}catch(ServiceRessourceNotFoundException $e){
			throw new HttpNotFoundException($rq, $e->getMessage());
		}


		$rs = $next->handle($rq);
		//après requete
		return $rs;
	}

}
