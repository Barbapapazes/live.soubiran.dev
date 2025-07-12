<?php

declare(strict_types=1);

use App\Events\CelebrationOver;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

beforeEach(function () {
    Event::fake();

    Config::set('services.confetti.key', 'valid-key');
});

it('requires a key', function () {
    $this->get(route('api.celebrate'))
        ->assertForbidden();
});

it('triggers a celebrate event', function () {
    $this->get(route('api.celebrate', ['key' => config('services.confetti.key')]))
        ->assertOk();

    Event::assertDispatched(CelebrationOver::class);
});

it('logs the celebrate Event', function () {
    Log::shouldReceive('info')
        ->once()
        ->with('Celebrate triggered via API');

    $this->get(route('api.celebrate', ['key' => config('services.confetti.key')]));
});
