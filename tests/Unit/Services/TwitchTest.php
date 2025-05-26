<?php

declare(strict_types=1);

use App\Services\Twitch;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    $this->twitchTokenUrl = 'https://id.twitch.tv/oauth2/token';
    $this->twitchTokenResponse = [
        'access_token' => 'app_access_token',
        'expires_in' => 5552416,
    ];

    $this->twitchSubscribeUrl = 'https://api.twitch.tv/helix/eventsub/subscriptions';
    $this->twitchSubscriptionsUrl = 'https://api.twitch.tv/helix/eventsub/subscriptions';
});

it('verifies a valid Twitch event', function () {
    $signature = 'sha256='.hash_hmac('sha256', '12345'.'timestamp'.'body', config('services.twitch.secret'));

    $messageId = '12345';
    $timestamp = 'timestamp';
    $body = 'body';

    $isValid = app()->make(Twitch::class)->isValidTwitchEvent($signature, $messageId, $timestamp, $body);

    expect($isValid)->toBeTrue();
});

it('returns false for an invalid Twitch event', function () {
    $signature = 'sha256=invalid_signature';

    $messageId = '12345';
    $timestamp = 'timestamp';
    $body = 'body';

    $isValid = app()->make(Twitch::class)->isValidTwitchEvent($signature, $messageId, $timestamp, $body);

    expect($isValid)->toBeFalse();
});

it('fires a request to get app access token', function () {
    Http::fake([
        $this->twitchTokenUrl => Http::response($this->twitchTokenResponse),
    ]);

    app()->make(Twitch::class)->getAppAccessToken();

    Http::assertSent(function ($request) {
        return $request->url() === $this->twitchTokenUrl
            && $request->method() === 'POST'
            && $request->hasHeader('Content-Type', 'application/x-www-form-urlencoded')
            && $request['client_id'] === config('services.twitch.client_id')
            && $request['client_secret'] === config('services.twitch.client_secret')
            && $request['grant_type'] === 'client_credentials';
    });
});

it('gets the app access token', function () {
    Http::fake([
        $this->twitchTokenUrl => Http::response($this->twitchTokenResponse),
    ]);

    $appAccessToken = app()->make(Twitch::class)->getAppAccessToken();

    expect($appAccessToken)->toBe('app_access_token');
});

it('returns the cached app access token', function () {
    Cache::put('twitch_app_access_token', 'cached_app_access_token', now()->addMinutes(5));

    $appAccessToken = app()->make(Twitch::class)->getAppAccessToken();

    expect($appAccessToken)->toBe('cached_app_access_token');

    Http::assertNothingSent();
});

it('caches the app access token with the expired time', function () {
    Http::fake([
        $this->twitchTokenUrl => Http::response($this->twitchTokenResponse),
    ]);

    app()->make(Twitch::class)->getAppAccessToken();

    expect(Cache::get('twitch_app_access_token'))->toBe('app_access_token');

    Carbon::setTestNow(now()->addSeconds($this->twitchTokenResponse['expires_in']));

    expect(Cache::has('twitch_app_access_token'))->toBeFalse();
});

it('throws an error if the request to get app access token fails', function () {
    Http::fake([
        $this->twitchTokenUrl => 500,
    ]);

    expect(fn () => app()->make(Twitch::class)->getAppAccessToken())
        ->toThrow(Exception::class, 'Failed to get app access token from Twitch');
});

it('fires a request to subscribe to a Twitch event', function () {
    Http::fake([
        $this->twitchTokenUrl => Http::response($this->twitchTokenResponse),
        $this->twitchSubscribeUrl => Http::response([]),
    ]);

    app()->make(Twitch::class)->subscribe('user_id', 'event');

    Http::assertSent(function ($request) {
        return $request->url() === $this->twitchSubscribeUrl
            && $request->method() === 'POST'
            && $request->hasHeader('Authorization', 'Bearer app_access_token')
            && $request->hasHeader('Client-Id', config('services.twitch.client_id'))
            && $request->hasHeader('Content-Type', 'application/json')
            && $request['type'] === 'event'
            && $request['version'] === '1'
            && $request['condition']['broadcaster_user_id'] === 'user_id'
            && $request['condition']['user_id'] === 'user_id'
            && $request['transport']['method'] === 'webhook'
            && $request['transport']['callback'] === config('services.twitch.callback_url').'/api/webhook/twitch'
            && $request['transport']['secret'] === config('services.twitch.secret');
    });
});

it('throws an error if the request to subscribe fails', function () {
    Http::fake([
        $this->twitchTokenUrl => Http::response($this->twitchTokenResponse),
        $this->twitchSubscribeUrl => 500,
    ]);

    expect(fn () => app()->make(Twitch::class)->subscribe('user_id', 'event'))
        ->toThrow(Exception::class, 'Failed to subscribe to Twitch event');
});

it('gets the list of Twitch subscriptions', function () {
    Http::fake([
        $this->twitchTokenUrl => Http::response($this->twitchTokenResponse),
        $this->twitchSubscriptionsUrl => Http::response(['data' => [['id' => 'subscription_id', 'status' => 'enabled']]]),
    ]);

    $subscriptions = app()->make(Twitch::class)->getSubscriptions();

    expect($subscriptions)->toBe([['id' => 'subscription_id', 'status' => 'enabled']]);
});

it('throws an error if the request to get subscriptions fails', function () {
    Http::fake([
        $this->twitchTokenUrl => Http::response($this->twitchTokenResponse),
        $this->twitchSubscriptionsUrl => 500,
    ]);

    expect(fn () => app()->make(Twitch::class)->getSubscriptions())
        ->toThrow(Exception::class, 'Failed to get Twitch subscriptions');
});

it('unsubscribes from a Twitch event', function () {
    $subscriptionId = 'subscription_id';

    Http::fake([
        $this->twitchTokenUrl => Http::response($this->twitchTokenResponse),
        $this->twitchSubscriptionsUrl => Http::response([]),
    ]);

    app()->make(Twitch::class)->unsubscribe($subscriptionId);

    Http::assertSent(function ($request) use ($subscriptionId) {
        return $request->url() === $this->twitchSubscriptionsUrl
            && $request->method() === 'DELETE'
            && $request->hasHeader('Authorization', 'Bearer app_access_token')
            && $request->hasHeader('Client-Id', config('services.twitch.client_id'))
            && $request['id'] === $subscriptionId;
    });
});

it('throws an error if the request to unsubscribe fails', function () {
    $subscriptionId = 'subscription_id';

    Http::fake([
        $this->twitchTokenUrl => Http::response($this->twitchTokenResponse),
        $this->twitchSubscriptionsUrl => 500,
    ]);

    expect(fn () => app()->make(Twitch::class)->unsubscribe($subscriptionId))
        ->toThrow(Exception::class, 'Failed to unsubscribe from Twitch event');
});
