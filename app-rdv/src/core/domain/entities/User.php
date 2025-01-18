<?php

namespace toubeelib\rdv\core\domain\entities;

class User extends Entity{
    protected string $email, $password;
    protected  int $role;
    public const  PATIENT = 0;
    public const  PERSONNEL_MEDICALE = 5;
    public const  PRATICIENS = 10;

    public function __construct(string $id,string $email, string $password, int $role)
    {
        $this->id=$id;
        $this->email=$email;
        $this->password=$password;
        $this->role=$role;
    }

}
