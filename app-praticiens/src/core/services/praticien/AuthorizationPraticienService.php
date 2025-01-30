<?php
namespace toubeelib\praticiens\core\services\praticien;
use Psr\Container\ContainerInterface;

class AuthorizationPraticienService implements AuthorizationPraticienServiceInterface{
    public function __construct(ContainerInterface $co)
    {
    }
    public function isGranted(string $userId, int $operation, string $ressourceId, int $role): bool
    {
        if($role === 10 && $userId === $ressourceId){
            return true;
        }else
            return false;
    

    }

}
