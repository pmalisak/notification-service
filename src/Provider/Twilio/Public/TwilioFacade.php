<?php

declare(strict_types=1);

namespace App\Provider\Twilio\Public;

use Psr\Log\LoggerInterface;
use Twilio\Exceptions\RestException;
use Twilio\Rest\Client;

class TwilioFacade
{
    public function __construct(
        private string $sid,
        private string $token,
        private string $phoneFrom,
        private LoggerInterface $logger,
    ) {
    }

    public function sendSms(string $to, string $message): bool
    {
        $client = new Client($this->sid, $this->token);
        try {
            $message = $client->messages->create(
                $to,
                [
                    'from' => $this->phoneFrom,
                    'body' => $message
                ]
            );
        } catch (\Exception|RestException $e) {
            $this->logger->error('Twilio response error: ' . $e->getMessage());
            return false;
        }

        return (bool)$message->sid;
    }
}
