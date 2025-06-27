<?php

use App\Services\Soubiran;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Config::set('services.soubiran.webhook_url', 'https://example.com/webhook');
    Config::set('services.soubiran.webhook_secret', 'test_secret');
});

it('sends event with correct signature and payload', function () {
    Http::fake();

    $event = 'test_event';
    $payload = ['foo' => 'bar'];

    $signature = 'sha256=' . hash_hmac('sha256', json_encode($payload), config('services.soubiran.webhook_secret'));

    app()->make(Soubiran::class)->sendEvent($event, $payload);

    Http::assertSent(function ($request) use ($event, $signature) {
        return $request->url() === config('services.soubiran.webhook_url')
            && $request['foo'] === 'bar'
            && $request->header('X-Soubiran-Signature-256')[0] === $signature
            && $request->header('X-Soubiran-Event')[0] === $event
            && $request->header('Content-Type')[0] === 'application/json';
    });
});
