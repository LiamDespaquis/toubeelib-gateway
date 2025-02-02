<?php

namespace toubeelib\infrastructure\repositories;

use DI\Container;
use Monolog\Logger;
use toubeelib\core\domain\entities\User;
use toubeelib\core\repositoryInterfaces\AuthRepositoryInterface;

class PgAuthRepository implements AuthRepositoryInterface
{
    protected \PDO $pdo;
    protected Logger $loger;

    public function __construct(Container $co)
    {
        $this->pdo = $co->get('pdo.auth');
        $this->loger = $co->get(Logger::class)->withName("PgAuth");

    }
    public function getUser(string $id): User
    {
        try {
            $query = 'select * from users where users.id=:id;';
            $rq = $this->pdo->prepare($query);
            $rq->execute(['id' => $id]);
            $user = $rq->fetch();
            if(!$user) {
                throw new RepositoryEntityNotFoundException('Utilisateur non trouvé');
            }
            return new User($user['id'], $user['email'], $user['password'], $user['role']);
        } catch(\PDOException $e) {
            $this->loger->error("PgAuthRep ". $e->getMessage());
            throw new RepositoryEntityNotFoundException($e->getMessage());
        }
    }

    public function createUser(User $user): User
    {

    }

    public function deletUser(string $id): void
    {
    }

    public function getUserByMail(string $email): User
    {
        try {
            $query = 'select * from users where users.email=:email;';
            $rq = $this->pdo->prepare($query);
            $rq->execute(['email' => $email]);
            $user = $rq->fetch();
            if(!$user) {
                throw new RepositoryEntityNotFoundException('Utilisateur non trouvé');
            }
            return new User($user['id'], $user['email'], $user['password'], $user['role']);
        } catch(\PDOException $e) {
            $this->loger->error($e->getMessage());
            throw new RepositoryEntityNotFoundException($e->getMessage());
        }
    }

}
