<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class SequenceService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getNextGroupeCode(): string
    {
        $conn = $this->entityManager->getConnection();
        $result = $conn->fetchAssociative('SELECT nextval(seq_groupe) as next_grp');
        return $result['next_grp'];
    }
}
