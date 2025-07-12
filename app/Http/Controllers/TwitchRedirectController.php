<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\TwitchProvider;

class TwitchRedirectController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): RedirectResponse
    {
        /** @var TwitchProvider $twitchProvider */
        $twitchProvider = Socialite::driver('twitch');

        return $twitchProvider->scopes(['user:bot', 'user:read:chat'])->redirect();
    }
}
