<?php

namespace toubeelib\rdv\core\services;

use toubeelib\rdv\core\dto\AuthDTO;
use toubeelib\rdv\core\dto\CredentialsDTO;

interface ServiceAuthInterface {
	public function createUser(CredentialsDTO $credentials, int $role): string;
	public function byCredentials(CredentialsDTO $credentials): AuthDTO;

}
