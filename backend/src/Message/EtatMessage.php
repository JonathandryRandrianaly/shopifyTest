<?php

namespace App\Message;

final class EtatMessage
{
    private $name;
    private $dateDebut;
    private $dateFin;

    public function __construct($name,$dateDebut,$dateFin)
    {
        $this->name = $name;
        $this->name = $dateDebut;
        $this->name = $dateFin;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDateDebut(): string
    {
        return $this->dateDebut;
    }

    public function getDateFin(): string
    {
        return $this->dateFin;
    }
}
