<?php

namespace toubeelib\praticiens\core\dto;

use Exception;
use JsonSerializable;
use Respect\Validation\Validatable;

abstract class DTO implements JsonSerializable
{
    public function jsonSerialize(): array
    {

        $retour =  get_object_vars($this);
        unset($retour['businessValidator']);
        return $retour;
    }
    protected ?Validatable $businessValidator = null;


    public function __get(string $name):mixed {
        return property_exists($this, $name) ? $this->$name : throw new Exception(static::class . ": Property $name does not exist");
    }

    public function toJSON(): string {
        return json_encode($this, JSON_PRETTY_PRINT);
    }

    public function setBusinessValidator(Validatable $validator): void {
        $this->businessValidator = $validator;
    }
    public function validate(): void {
        $this->businessValidator ? $this->businessValidator->assert($this): null;
    }
}
