<?php

declare(strict_types=1);

use App\Events\ConfettiExplode;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

beforeEach(function () {
    Config::set('services.confetti.key', 'valid-key');
});

it('requires a key', function () {
    $this->get(route('confetti.explode'))
        ->assertStatus(403);
});

it('returns ok with a valid key', function () {
    Event::fake();

    $this->get(route('confetti.explode', ['key' => config('services.confetti.key')]))
        ->assertOk();
});

it('triggers a confetti event', function () {
    Event::fake();

    $this->get(route('confetti.explode', ['key' => config('services.confetti.key')]))
        ->assertOk();

    Event::assertDispatched(ConfettiExplode::class);
});

it('logs the confetti explosion', function () {
    Event::fake();

    Log::shouldReceive('info')
        ->once()
        ->with('Confetti explosion triggered via GET request');

    $this->get(route('confetti.explode', ['key' => config('services.confetti.key')]));
});
