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
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use toubeelib\praticiens\core\services\praticien\AuthorizationPraticienServiceInterface;

class AuthzPraticiens implements MiddlewareInterface{

	protected Logger $loger;
	protected AuthorizationPraticienServiceInterface $authpraticienservice;
	protected string $key, $algo; 

	
	public function __construct(Container $co)
	{
		$this->loger = $co->get(Logger::class)->withName("AutnzRDVMiddleware");
		$this->authpraticienservice = $co->get(AuthorizationPraticienServiceInterface::class);
		$this->key = getenv('JWT_SECRET_KEY');
		$this->algo = $co->get('token.jwt.algo');
	}

	public function process(ServerRequestInterface $rq, RequestHandlerInterface $next): ResponseInterface
	{
		$idPraticien = RouteContext::fromRequest($rq)->getRoute()->getArgument('id');
		$token = $rq->getHeader("Authorization")[0];
        $token = sscanf($token, "Bearer %s")[0];
		$decoded_token = (array) JWT::decode($token, new Key($this->key, $this->algo));

		if( $this->authpraticienservice->isGranted($decoded_token['sub'], 1, $idPraticien, $decoded_token['role'])){
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
