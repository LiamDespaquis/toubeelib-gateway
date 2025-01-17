<?php
namespace toubeelib\praticiens\middlewares;

use DI\Container;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Routing\RouteContext;
use toubeelib\praticiens\core\services\praticien\AuthorizationPraticienServiceInterface;

class AuthzPraticiens implements MiddlewareInterface{

	protected Logger $loger;
	protected AuthorizationPraticienServiceInterface $authpraticienservice;

	
	public function __construct(Container $co)
	{
		$this->loger = $co->get(Logger::class)->withName("AutnzRDVMiddleware");
		$this->authpraticienservice = $co->get(AuthorizationPraticienServiceInterface::class);
	}

	public function process(ServerRequestInterface $rq, RequestHandlerInterface $next): ResponseInterface
	{
		$idPraticien = RouteContext::fromRequest($rq)->getRoute()->getArgument('id');
		$user = $rq->getAttribute('user');
		// try{
		if( $this->authpraticienservice->isGranted($user->id, 1, $idPraticien, $user->role)){
			return $next->handle($rq);
		}else{
			throw new HttpUnauthorizedException($rq, "Accès au praticien $idPraticien non authorisé");
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
