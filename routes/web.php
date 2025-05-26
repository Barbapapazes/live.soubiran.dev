<?php

declare(strict_types=1);

use App\Http\Controllers\EndOverlayController;
use App\Http\Controllers\MainOverlayController;
use App\Http\Controllers\StartOverlayController;
use Illuminate\Support\Facades\Route;
use App\Jobs\SubscribeSubscription;
use App\Models\User;
use App\Services\Twitch;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\TwitchProvider;

Route::prefix('overlays')->name('overlays.')->group(function () {
    Route::get('start', StartOverlayController::class)
        ->name('start');
    Route::get('overlays/end', EndOverlayController::class)
        ->name('end');
    Route::get('overlays/main', MainOverlayController::class)
        ->name('main');
});


Route::get('/auth/twitch/redirect', function () {
    /** @var TwitchProvider $twitchProvider */
    $twitchProvider = Socialite::driver('twitch');

    return $twitchProvider->scopes(['user:bot', 'user:read:chat'])->redirect();
});

Route::get('/auth/twitch/callback', function () {
    $twitchUser = Socialite::driver('twitch')->user();

    $user = User::where('twitch_id', $twitchUser->getId())->first();

    if (! $user) {
        $user = User::create([
            'twitch_id' => $twitchUser->getId(),
        ]);
    }

    if (! $user->has_access) {
        return response('You do not have access to this application.', 403);
    }

    SubscribeSubscription::dispatch($user->id, $user->twitch_id, 'channel.chat.message');

    return 'ok';
});
