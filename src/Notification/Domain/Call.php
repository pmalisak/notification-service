<?php

declare(strict_types=1);

namespace App\Notification\Domain;

use App\Notification\Domain\Provider\Provider;
use Symfony\Component\Uid\Uuid;

class Call
{
    private CallStatus $status = CallStatus::NEW;
    private \DateTimeImmutable $createdAt;

    public function __construct(
        private Uuid                $id,
        private Notification        $notification,
        private Channel             $channel,
        private Provider            $provider,
        private ?\DateTimeImmutable $scheduledAt = null,
    ) {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getChannel(): Channel
    {
        return $this->channel;
    }

    public function start(): void
    {
        $this->status = CallStatus::STARTED;
    }

    public function fail(): void
    {
        $this->status = CallStatus::FAILED;
    }

    public function complete(): void
    {
        $this->status = CallStatus::COMPLETED;
    }

    public function getStatus(): CallStatus
    {
        return $this->status;
    }

    public function getProvider(): Provider
    {
        return $this->provider;
    }
}
