<?php

declare(strict_types=1);

use App\Jobs\SubscribeSubscription;
use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    $this->user = User::factory()->create();
    $this->subscription = Subscription::factory()->for($this->user)->create();

    $this->payload = [
        'subscription' => [
            'id' => $this->subscription->subscription_id,
            'type' => $this->subscription->event,
            'condition' => [
                'broadcaster_user_id' => $this->user->twitch_id,
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
        'Twitch-Eventsub-Message-Type' => 'revocation',
    ]);
});

it('deletes the subscription when Twitch revokes the subscription', function () {
    Queue::fake();

    $this->postJson(route('webhook.twitch'), $this->payload);

    $this->assertDatabaseMissing(Subscription::class, [
        'subscription_id' => $this->subscription->subscription_id,
    ]);
});

it('dispatch an event to resubscribe to the same event', function () {
    Queue::fake();

    $this->postJson(route('webhook.twitch'), $this->payload);

    Queue::assertPushed(function (SubscribeSubscription $job) {
        return $job->userId === $this->user->id &&
            $job->broadcasterUserId === $this->user->twitch_id &&
            $job->event === $this->subscription->event;
    });
});
