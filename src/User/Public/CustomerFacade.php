<?php

declare(strict_types=1);

namespace App\User\Public;

use App\User\Exception\NotFoundException;
use Symfony\Component\Uid\Uuid;

final readonly class CustomerFacade
{
    public function getCustomer(Uuid $customerId): CustomerData
    {
        $exampleData = [
            'c5d4fa09-62d8-47a9-b3ac-09d878252556' => [
                '+48668820099',
                'foo@example.com',
            ],
            '8a8de5a8-fa71-4085-9c57-8a69a9c55455' => [
                '+48668820098',
                'bar@example.com',
            ],
        ];

        $data = $exampleData[(string) $customerId] ?? throw new NotFoundException('Customer not found');

        return new CustomerData($customerId, ...$data);
    }
}
