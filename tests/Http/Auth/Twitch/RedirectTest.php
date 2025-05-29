<?php

declare(strict_types=1);

use Laravel\Socialite\Facades\Socialite;

test('redirects for authorization', function () {
    Socialite::shouldReceive('driver')
        ->with('twitch')
        ->andReturnSelf()
        ->shouldReceive('scopes')
        ->with(['user:bot', 'user:read:chat'])
        ->andReturnSelf()
        ->shouldReceive('redirect')
        ->once()
        ->andReturnSelf();

    $this->get(route('auth.twitch.redirect'));

    expect(true)->toBeTrue();
});
