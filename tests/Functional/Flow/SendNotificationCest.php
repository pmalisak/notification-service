<?php

declare(strict_types=1);

namespace Functional\Flow;

use App\Notification\Domain\Notification;
use App\Notification\Domain\Status;
use App\Tests\Support\FunctionalTester;

class SendNotificationCest
{
    public function notificationCompleted(FunctionalTester $I): void
    {
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPost('/notification', [
            'customerId' => 'c5d4fa09-62d8-47a9-b3ac-09d878252556',
            'channels' => ['email'],
            'message' => 'Test message',
        ]);

        $I->seeResponseIsJson();

        $response = json_decode($I->grabResponse());

        $notification = $I->grabEntityFromRepository(Notification::class, ['id' => $response->notificationId]);

        $I->assertEquals(Status::COMPLETED, $notification->getStatus());
        $I->assertCount(1, $notification->getCalls());
    }
}
