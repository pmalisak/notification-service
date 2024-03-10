<?php

declare(strict_types=1);

namespace App\User\Public;

use Symfony\Component\Uid\Uuid;

final readonly class CustomerData
{
    public function __construct(
        public Uuid $id,
        public string $phoneNumber,
        public string $email,
    ) {
    }
}
