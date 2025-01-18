<?php
namespace toubeelib\rdv\providers\auth;

use DI\Container;
use toubeelib\rdv\core\dto\AuthDTO;
use toubeelib\rdv\core\dto\CredentialsDTO;
use toubeelib\rdv\core\services\ServiceAuthBadPasswordException;
use toubeelib\rdv\core\services\ServiceAuthInterface;

class JWTAuthnProvider implements AuthnProviderInterface{
	protected ServiceAuthInterface $serviceAuth;
	protected JWTManager $jwtManager;
	public function __construct(Container $co)
	{
		$this->serviceAuth = $co->get(ServiceAuthInterface::class);
		$this->jwtManager = $co->get(JWTManager::class);
	}
	public function register(CredentialsDTO $credentials): void
	{
	}

	public function signin(CredentialsDTO $credentials): AuthDTO
	{
		$user = $this->serviceAuth->byCredentials($credentials);
		$token = $this->jwtManager->createAcessToken($user);
		$authdto = new AuthDTO($user->id,$user->role);
		$authdto->setAtoken($token);
		return $authdto;

	}

	public function refresh(AuthDTO $credentials): AuthDTO
	{
	}

	public function getSignedInUser(string $atoken): AuthDTO
	{
		try{
		$token = $this->jwtManager->decodeToken($atoken);
		$authDto = new AuthDTO($token['sub'], $token['role']);
		return $authDto;
		}
		catch(\Exception $e){
			throw new AuthInvalidException($e->getMessage());
		}

	}

}
