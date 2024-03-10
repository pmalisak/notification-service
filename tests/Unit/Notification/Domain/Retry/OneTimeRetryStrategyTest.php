<?php

declare(strict_types=1);

namespace Tests\App\Unit\Notification\Domain\Retry;

use App\Notification\Domain\Channel;
use App\Notification\Domain\Notification;
use App\Notification\Domain\Provider\Provider;
use App\Notification\Domain\Retry\OneTimeRetryStrategy;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;

class OneTimeRetryStrategyTest extends TestCase
{
    private Notification $notification;
    private OneTimeRetryStrategy $strategy;

    protected function setUp(): void
    {
        $this->notification = new Notification(
            Uuid::v4(),
            Uuid::v4(),
            [Channel::SMS],
            'test message',
        );
        $this->strategy = new OneTimeRetryStrategy();
    }

    public function testOneCallWillAllowRetry(): void
    {
        $this->notification->create(Channel::SMS, Provider::MOCKER);
        $calls = $this->notification->getCalls();

        $this->assertTrue($this->strategy->retryAllowed([Provider::MOCKER], $calls));
    }

    public function testMultipleProvidersRetryAllowed(): void
    {
        $this->notification->create(Channel::SMS, Provider::MOCKER);
        $this->notification->create(Channel::SMS, Provider::MOCKER);
        $calls = $this->notification->getCalls();

        $this->assertTrue($this->strategy->retryAllowed([Provider::MOCKER, Provider::TWILIO], $calls));
    }

    public function testRetryNotAllowed(): void
    {
        $this->notification->create(Channel::SMS, Provider::MOCKER);
        $this->notification->create(Channel::SMS, Provider::MOCKER);
        $calls = $this->notification->getCalls();

        $this->assertFalse($this->strategy->retryAllowed([Provider::MOCKER], $calls));
    }
}
