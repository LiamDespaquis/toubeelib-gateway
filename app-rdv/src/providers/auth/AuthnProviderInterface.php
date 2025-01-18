<?php
namespace toubeelib\rdv\providers\auth;

use toubeelib\rdv\core\dto\AuthDTO;

use toubeelib\rdv\core\dto\CredentialsDTO;

interface AuthnProviderInterface{
	public function register(CredentialsDTO $credentials):void;
	public function signin(CredentialsDTO $credentials):AuthDTO;
	public function refresh(AuthDTO $credentials):AuthDTO;
	public function getSignedInUser(string  $atoken):AuthDTO;
}
