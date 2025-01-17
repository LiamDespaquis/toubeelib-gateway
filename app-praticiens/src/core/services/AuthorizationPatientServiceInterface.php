<?php
namespace toubeelib\praticiens\core\services;

interface AuthorizationPatientServiceInterface
{
    public function isGranted(string $userId, int $operation, string $ressourceId, int $role): bool;

}
