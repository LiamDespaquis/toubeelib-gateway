<?php
namespace toubeelib\praticiens\providers\auth;

use toubeelib\praticiens\core\dto\AuthDTO;
use toubeelib\praticiens\core\dto\CredentialsDTO;

interface AuthnProviderInterface{
	public function register(CredentialsDTO $credentials):void;
	public function signin(CredentialsDTO $credentials):AuthDTO;
	public function refresh(AuthDTO $credentials):AuthDTO;
	public function getSignedInUser(string  $atoken):AuthDTO;
}
