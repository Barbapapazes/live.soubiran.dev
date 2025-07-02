<?php

declare(strict_types=1);

use App\Events\ConfettiExplode;
use App\Events\ConfettiLocked;
use App\Events\ReceivedMessage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    $this->payload = [
        'event' => [
            'chatter_user_name' => 'testuser',
            'message' => [
                'text' => '!confetti',
            ],
            'color' => '#000000',
        ],
    ];

    $this->withHeaders(headers($this->payload));
});

it('broadcasts every messages', function () {
    Event::fake();

    $payload = [
        'event' => [
            'chatter_user_name' => 'testuser',
            'message' => [
                'text' => 'Hello, world!',
            ],
            'color' => '#000000',
        ],
    ];

    $this
        ->withHeaders(headers($payload))
        ->postJson(route('webhook.twitch'), $payload);

    Event::assertDispatched(ReceivedMessage::class, function ($event) {
        return $event->message === 'Hello, world!' &&
               $event->username === 'testuser' &&
               $event->color === '#000000';
    });
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

it('sends a locked confetti event when the confetti command is ignored due to an existing lock', function () {
    Event::fake();

    $this->postJson(route('webhook.twitch'), $this->payload);
    Event::assertDispatched(ConfettiExplode::class, 1);

    // Simulate a second request while the lock is still active
    $this->postJson(route('webhook.twitch'), $this->payload);

    Event::assertDispatched(ConfettiLocked::class);
});

/**
 * @param  array<string, mixed>  $payload
 * @return array{'Twitch-Eventsub-Message-Signature': string, 'Twitch-Eventsub-Message-Id': string, 'Twitch-Eventsub-Message-Timestamp': string, 'Twitch-Eventsub-Message-Type': string}
 */
function headers(array $payload): array
{
    $messageId = '12345';
    $timestamp = Carbon::now()->toIso8601String();
    $signature = 'sha256='.hash_hmac('sha256', $messageId.$timestamp.json_encode($payload), config('services.twitch.secret'));

    return [
        'Twitch-Eventsub-Message-Signature' => $signature,
        'Twitch-Eventsub-Message-Id' => $messageId,
        'Twitch-Eventsub-Message-Timestamp' => $timestamp,
        'Twitch-Eventsub-Message-Type' => 'notification',
    ];
}
