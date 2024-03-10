<?php

declare(strict_types=1);

namespace App\Notification\Application\Provider\Result;

final readonly class Result
{
    public function __construct(
        public bool $success,
    ) {
    }
}
