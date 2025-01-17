<?php
namespace toubeelib\praticiens\core\services;

use DI\Container;
use toubeelib\praticiens\core\dto\AuthDTO;
use toubeelib\praticiens\core\dto\CredentialsDTO;
use toubeelib\praticiens\core\repositoryInterfaces\AuthRepositoryInterface;
use toubeelib\praticiens\core\repositoryInterfaces\RepositoryEntityNotFoundException;

class ServiceAuth implements ServiceAuthInterface{
	protected AuthRepositoryInterface $repositoryAuth;
	public function __construct(Container $co)
	{
		$this->repositoryAuth= $co->get(AuthRepositoryInterface::class);
	}
	public function createUser(CredentialsDTO $credentials, int $role): string
	{
	}

	/*
	* Verifie les credentials avec ceux de la base de donnée
	*/
	public function byCredentials(CredentialsDTO $credentials): AuthDTO
	{
		try{
			$user = $this->repositoryAuth->getUserByMail($credentials->email);
			if(!password_verify($credentials->password,$user->password)){

				throw new ServiceAuthBadPasswordException("Mauvais mot de passe");
			}
			return new AuthDTO($user->id, $user->role);
		}catch(RepositoryEntityNotFoundException $e){

			throw new ServiceAuthUserNotFoundException("Utilisateur $credentials->id non trouvé");
		}
	}

}
