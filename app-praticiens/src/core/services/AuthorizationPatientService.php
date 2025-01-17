<?php
namespace toubeelib\praticiens\core\services;
use Psr\Container\ContainerInterface;

class AuthorizationPatientService implements AuthorizationPatientServiceInterface{
    public function __construct(ContainerInterface $co)
    {
    }
    public function isGranted(string $userId, int $operation, string $ressourceId, int $role): bool
    {
        if($role === 0 && $userId === $ressourceId){
            return true;
        }else
            return false;
    

    }

}
