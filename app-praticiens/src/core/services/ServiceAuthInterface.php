<?php

namespace toubeelib\praticiens\core\services;

use toubeelib\praticiens\core\dto\AuthDTO;
use toubeelib\praticiens\core\dto\CredentialsDTO;

interface ServiceAuthInterface {
	public function createUser(CredentialsDTO $credentials, int $role): string;
	public function byCredentials(CredentialsDTO $credentials): AuthDTO;

}
