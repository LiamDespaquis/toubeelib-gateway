<?php

namespace toubeelib\rdv\core\domain\entities\praticien;

use toubeelib\rdv\core\domain\entities\Entity;
use toubeelib\rdv\core\dto\SpecialiteDTO;

class Specialite extends Entity
{

    protected string $label;
    protected string $description;

    public function __construct(string $ID, string $label, string $description = "")
    {
        $this->id = $ID;
        $this->label = $label;
        $this->description = $description;
    }

    public function toDTO(): SpecialiteDTO
    {
        return new SpecialiteDTO($this->id, $this->label, $this->description);

    }
}
