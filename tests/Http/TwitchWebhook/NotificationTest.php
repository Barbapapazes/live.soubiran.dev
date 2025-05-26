<?php

declare(strict_types=1);

use App\Events\ConfettiExplode;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    $this->payload = [
        'event' => [
            'message' => [
                'text' => '!confetti',
            ],
        ],
    ];

    $messageId = '12345';
    $timestamp = Carbon::now()->toIso8601String();
    $signature = 'sha256='.hash_hmac('sha256', $messageId.$timestamp.json_encode($this->payload), config('services.twitch.secret'));
    $this->withHeaders([
        'Twitch-Eventsub-Message-Signature' => $signature,
        'Twitch-Eventsub-Message-Id' => $messageId,
        'Twitch-Eventsub-Message-Timestamp' => $timestamp,
        'Twitch-Eventsub-Message-Type' => 'notification',
    ]);
});

it('triggers a confetti event when the confetti command is detected', function () {
    Event::fake();

    $this->postJson(route('webhook.twitch'), $this->payload);

    Event::assertDispatched(ConfettiExplode::class);
});

it('locks the confetti event for 10 seconds once it is triggered', function () {
    Event::fake();

    $this->postJson(route('webhook.twitch'), $this->payload);
    $this->postJson(route('webhook.twitch'), $this->payload);

    Event::assertDispatched(ConfettiExplode::class, 1);

    Carbon::setTestNow(now()->addSeconds(10));

    $this->postJson(route('webhook.twitch'), $this->payload);

    Event::assertDispatched(ConfettiExplode::class, 2);
});
