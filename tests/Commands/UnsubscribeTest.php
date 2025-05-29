<?php

declare(strict_types=1);

use App\Console\Commands\UnsubscribeCommand;
use App\Models\Subscription;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->twitchTokenUrl = 'https://id.twitch.tv/oauth2/token';
    $this->twitchTokenResponse = [
        'access_token' => 'app_access_token',
        'expires_in' => 5552416,
    ];

    $this->twitchSubscriptionsUrl = 'https://api.twitch.tv/helix/eventsub/subscriptions';
});

it('unsubscribes from all Twitch events', function () {
    $subscriptionId = 'subscription_id';

    Http::fake([
        $this->twitchTokenUrl => Http::response($this->twitchTokenResponse),
        $this->twitchSubscriptionsUrl => Http::response([
            'data' => [[
                'id' => $subscriptionId,
                'type' => 'channel.chat.message',
            ]],
        ]),
    ]);

    $this->artisan(UnsubscribeCommand::class)
        ->expectsOutput('Unsubscribed from all Twitch events successfully.')
        ->assertExitCode(0);

    Http::assertSent(function ($request) use ($subscriptionId) {
        return $request->url() === $this->twitchSubscriptionsUrl
            && $request->method() === 'DELETE'
            && $request->hasHeader('Authorization', 'Bearer app_access_token')
            && $request->hasHeader('Client-Id', config('services.twitch.client_id'))
            && $request['id'] === $subscriptionId;
    });
});

it('deletes associated subscriptions', function () {
    $subscription = Subscription::factory()->create();

    Http::fake([
        $this->twitchTokenUrl => Http::response($this->twitchTokenResponse),
        $this->twitchSubscriptionsUrl => Http::response([
            'data' => [[
                'id' => $subscription->subscription_id,
                'type' => 'channel.chat.message',
            ]],
        ]),
    ]);

    $this->artisan(UnsubscribeCommand::class)
        ->expectsOutput('Unsubscribed from all Twitch events successfully.')
        ->assertExitCode(0);

    $this->assertDatabaseCount(Subscription::class, 0);
});
