<?php

namespace toubeelib\rdv\core\domain\entities;

abstract class Entity
{

    protected ?string $id=null;
    public function __get(string $name): mixed
    {
        return property_exists($this, $name) ? $this->$name : throw new \Exception(static::class . ": Property $name does not exist");
    }

    public function setId(string $ID): void
    {
        $this->id = $ID;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

}
