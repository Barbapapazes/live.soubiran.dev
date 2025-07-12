<?php

declare(strict_types=1);

use App\Events\CelebrationOver;
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

it('triggers a confetti event when the `!confetti` command is detected', function () {
    Event::fake();

    $this
        ->postJson(route('webhook.twitch'), $this->payload);

    Event::assertDispatched(CelebrationOver::class);
});

it('triggers a confetti event when the `!confettis` command is detected', function () {
    Event::fake();

    $this->payload['event']['message']['text'] = '!confettis'; // For french viewers

    $this
        ->withHeaders(headers($this->payload))
        ->postJson(route('webhook.twitch'), $this->payload);

    Event::assertDispatched(CelebrationOver::class);
});

it('triggers a confetti event when the `!celebrate` command is detected', function () {
    Event::fake();

    $this->payload['event']['message']['text'] = '!celebrate';

    $this
        ->withHeaders(headers($this->payload))
        ->postJson(route('webhook.twitch'), $this->payload);

    Event::assertDispatched(CelebrationOver::class);
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
