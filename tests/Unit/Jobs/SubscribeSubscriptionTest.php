<?php

declare(strict_types=1);

use App\Jobs\SubscribeSubscription;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->twitchTokenUrl = 'https://id.twitch.tv/oauth2/token';
    $this->twitchTokenResponse = [
        'access_token' => 'app_access_token',
        'expires_in' => 5552416,
    ];

    $this->twitchSubscribeUrl = 'https://api.twitch.tv/helix/eventsub/subscriptions';

    $this->userId = 1;
    $this->broadcasterUserId = '123456789';
    $this->event = 'channel.chat.message';
});

it('fires a request to subscribe to a Twitch event', function () {
    Http::fake([
        $this->twitchTokenUrl => Http::response($this->twitchTokenResponse),
        $this->twitchSubscribeUrl => Http::response([]),
    ]);

    SubscribeSubscription::dispatchSync($this->userId, $this->broadcasterUserId, $this->event);

    Http::assertSent(function ($request) {
        return $request->url() === $this->twitchSubscribeUrl
            && $request->method() === 'POST'
            && $request->hasHeader('Authorization', 'Bearer app_access_token')
            && $request->hasHeader('Client-Id', config('services.twitch.client_id'))
            && $request->hasHeader('Content-Type', 'application/json')
            && $request['type'] === 'channel.chat.message'
            && $request['version'] === '1'
            && $request['condition']['broadcaster_user_id'] === $this->broadcasterUserId
            && $request['condition']['user_id'] === $this->broadcasterUserId
            && $request['transport']['method'] === 'webhook'
            && $request['transport']['callback'] === config('services.twitch.callback_url').'/api/webhook/twitch'
            && $request['transport']['secret'] === config('services.twitch.secret');
    });
});

it('throws an exception if the request fails', function () {
    Http::fake([
        $this->twitchTokenUrl => Http::response($this->twitchTokenResponse),
        $this->twitchSubscribeUrl => Http::response([], 500),
    ]);

    SubscribeSubscription::dispatchSync($this->userId, $this->broadcasterUserId, $this->event);
})->throws(Exception::class, 'Failed to subscribe to Twitch event');

it('does fire a request to subscribe to a Twitch event if the subscription is already active', function () {
    $user = User::factory()->create();
    Subscription::factory()->for($user)->create([
        'event' => $this->event,
    ]);

    SubscribeSubscription::dispatchSync($user->id, $this->broadcasterUserId, $this->event);

    Http::assertNothingSent();
});
