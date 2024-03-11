<?php

declare(strict_types=1);

namespace App\Notification\Infrastructure\Doctrine\Repository;

use App\Notification\Domain\Notification;
use App\Notification\Domain\Repository\NotificationRepository;
use App\Notification\Infrastructure\Doctrine\Exception\NotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<Notification>
 */
class DoctrineNotificationRepository extends ServiceEntityRepository implements NotificationRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }
    public function get(Uuid $notificationId): Notification
    {
        return $this->find($notificationId) ?: throw new NotFoundException('Notification not found');
    }

    public function add(Notification $notification): void
    {
        $this->getEntityManager()->persist($notification);
    }
}
