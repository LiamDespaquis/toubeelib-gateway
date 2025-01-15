<?php
namespace toubeelib\providers\auth;

use DI\Container;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Monolog\Logger;
use toubeelib\core\dto\AuthDTO;

class JWTManager{

	protected int $tempsValidite;
	protected string $emmeteur, $audience;
	protected string $key, $algo;
	protected Logger $loger;
	

	public function __construct(Container $co)
	{
		$this->tempsValidite = $co->get('token.temps.validite');
		$this->emmeteur = $co->get('token.emmeteur');
		$this->audience = $co->get('token.audience');
		// $this->key = parse_ini_file($co->get('token.key.path'))['JWT_SECRET_KEY'];
		$this->key = getenv('JWT_SECRET_KEY');
		$this->algo = $co->get('token.jwt.algo');
		$this->loger = $co->get(Logger::class)->withName("JWTManager");;

	}

	public function createAcessToken(AuthDTO $user): string{
		/*
		* Données nécessaires pour créer le token:
		* temps de validité à partir de maintenant
		* emmetteur du token
		* audience du token 
		* sujet du token (id de l'user)
		*/
		$payload = [ 
			'iss'=> $this->emmeteur,
			'aud'=>$this->audience,
			'iat'=>time(),
			'exp'=>time()+$this->tempsValidite,
			'sub' => $user->id,
			'role' => $user->role,
		] ;

		return JWT::encode($payload, $this->key, $this->algo);


	}
	public function createRefresh(array $paylod): string{
	}

	public function decodeToken(string $token): array{
		try{

		return (array) JWT::decode($token, new Key($this->key, $this->algo));
		}catch(ExpiredException $e){
			// $this->loger->error($e->getMessage());
			$this->loger->error($e->getMessage());
			throw new AuthInvalidException($e->getMessage());
		}
	}
}
