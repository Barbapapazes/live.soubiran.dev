<?php

declare(strict_types=1);

use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->payload = [
        'challenge' => '123456789',
        'subscription' => [
            'id' => 'f1c2a387-161a-49f9-a165-0f21d7a4e1c4',
            'type' => 'channel.chat.message',
            'condition' => [
                'broadcaster_user_id' => $this->user->twitch_id,
            ],
        ],
    ];

    $messageId = '12345';
    $timestamp = Carbon::now()->toIso8601String();
    $signature = 'sha256='.hash_hmac('sha256', $messageId.$timestamp.json_encode($this->payload), config('services.twitch.secret'));
    $this
        ->withHeaders([
            'Twitch-Eventsub-Message-Signature' => $signature,
            'Twitch-Eventsub-Message-Id' => $messageId,
            'Twitch-Eventsub-Message-Timestamp' => $timestamp,
            'Twitch-Eventsub-Message-Type' => 'webhook_callback_verification',
        ]);
});

it('responds with the challenge', function () {
    $response = $this->postJson(route('webhook.twitch'), $this->payload);

    $response->assertStatus(200)
        ->assertHeader('Content-Type', 'text/plain; charset=UTF-8')
        ->assertContent($this->payload['challenge']);
});

it('stores the subscription when the challenge is received', function () {
    $this->postJson(route('webhook.twitch'), $this->payload);

    $this->assertDatabaseHas(Subscription::class, [
        'user_id' => $this->user->id,
        'subscription_id' => $this->payload['subscription']['id'],
        'event' => $this->payload['subscription']['type'],
    ]);
});
