<?php
namespace toubeelib\middlewares;

use DI\Container;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Routing\RouteContext;
use toubeelib\core\services\AuthorizationPatientServiceInterface;
use toubeelib\core\services\rdv\AuthorizationRendezVousServiceInterface;

class AuthzPatient implements MiddlewareInterface{

	protected Logger $loger;
	protected AuthorizationPatientServiceInterface $authpatientservice;

	
	public function __construct(Container $co)
	{
		$this->loger = $co->get(Logger::class)->withName("AutnzRDVMiddleware");
		$this->authpatientservice = $co->get(AuthorizationPatientServiceInterface::class);
	}

	public function process(ServerRequestInterface $rq, RequestHandlerInterface $next): ResponseInterface
	{
		$idPatient = RouteContext::fromRequest($rq)->getRoute()->getArgument('id');
		$user = $rq->getAttribute('user');
		// try{
		if( $this->authpatientservice->isGranted($user->id, 1, $idPatient, $user->role)){
			return $next->handle($rq);
		}else{
			throw new HttpUnauthorizedException($rq, "Accès au rdv $idPatient non authorisé");
		}
		// }
		// catch(\Error $e){
		// 	$this->loger->error($e->getMessage());
		// 	throw new HttpUnauthorizedException($rq, "Erreur lors de l'authentification veuillez verifier votre token");
		// }


		$rs = $next->handle($rq);
		//après requete
		return $rs;
	}

}
