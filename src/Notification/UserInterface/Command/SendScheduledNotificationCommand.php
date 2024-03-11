<?php

declare(strict_types=1);

namespace App\Notification\UserInterface\Command;

use App\Notification\Application\Command\SendNotificationCommand;
use App\Notification\Domain\CallStatus;
use App\Shared\Application\CQRS\CommandBus;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Uid\Uuid;

#[AsCommand(name: 'worker:notification:send-scheduled', description: 'Send scheduled notification')]
class SendScheduledNotificationCommand extends Command
{
    public function __construct(
        private Connection $connection,
        private CommandBus $commandBus,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sql = <<<SQL
            SELECT id, notification_id FROM notification_call WHERE status = :status AND scheduled_at < NOW() ORDER BY scheduled_at LIMIT 10
SQL;

        $result = $this->connection->executeQuery($sql, ['status' => CallStatus::NEW->value]);

        /**
         * @var array{notification_id: string, id: string} $call
         */
        foreach ($result->fetchAllAssociative() as $call) {
            $this->commandBus->dispatch(new SendNotificationCommand(
                Uuid::fromString($call['notification_id']),
                Uuid::fromString($call['id']),
            ));
        }

        return 0;
    }
}
