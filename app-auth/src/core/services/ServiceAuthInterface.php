<?php

namespace toubeelib\core\services;

use toubeelib\core\dto\AuthDTO;
use toubeelib\core\dto\CredentialsDTO;

interface ServiceAuthInterface {
	public function createUser(CredentialsDTO $credentials, int $role): string;
	public function byCredentials(CredentialsDTO $credentials): AuthDTO;

}
