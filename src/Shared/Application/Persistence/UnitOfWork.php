<?php

declare(strict_types=1);

namespace App\Shared\Application\Persistence;

interface UnitOfWork
{
    public function commit(): void;
}
