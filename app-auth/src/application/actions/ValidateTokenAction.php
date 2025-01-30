<?php

namespace toubeelib\application\actions;

use DI\Container;
use PHPUnit\Exception;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpUnauthorizedException;
use toubeelib\providers\auth\AuthInvalidException;
use toubeelib\application\renderer\JsonRenderer;
class ValidateTokenAction extends AbstractAction
{

    public function __construct(Container $co)
    {
        parent::__construct($co);
        
    }

    public function __invoke(ServerRequestInterface $rq, ResponseInterface $rs, array $args): ResponseInterface
    {
        if(!$rq->hasHeader("Authorization")){
        foreach($rq->getHeaders() as $s){
        	$this->loger->error($s[0]);
        	}
        
        	throw new HttpUnauthorizedException($rq, "Header Authorization manquante, veuillez vous enregistrer");
        }
        try{
        	$token = $rq->getHeader("Authorization")[0];
        	$token = sscanf($token, "Bearer %s");
        	if($token == null){
        		throw new \Exception("Mauvais token");
        	}
        	$token = $token[0];
        	$user = $this->authProvider->getSignedInUser($token);
        	$rq = $rq->withAttribute('user', $user);

            return JsonRenderer::render($rs, 200);



        }catch (AuthInvalidException $e){
        	$this->loger->error($e->getMessage());
            return JsonRenderer::render($rs, 401, ['error' => "Votre authentification n'est pas valide, veuillez vous reconnecter"]);
        }
        catch(\Error $e){
        	$this->loger->error($e->getMessage());
            return JsonRenderer::render($rs, 401, ['error' => "Erreur lors de l'authentification veuillez verifier votre token"]);
        }
    }
}
