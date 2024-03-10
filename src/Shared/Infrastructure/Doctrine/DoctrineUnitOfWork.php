<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Doctrine;

use App\Shared\Application\Persistence\UnitOfWork;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineUnitOfWork implements UnitOfWork
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function commit(): void
    {
        $this->entityManager->flush();
    }
}
