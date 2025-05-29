<?php

declare(strict_types=1);

use App\Http\Controllers\BreakOverlayController;
use App\Http\Controllers\EndOverlayController;
use App\Http\Controllers\MainOverlayController;
use App\Http\Controllers\StartOverlayController;
use App\Http\Controllers\TwitchCallbackController;
use App\Http\Controllers\TwitchRedirectController;
use Illuminate\Support\Facades\Route;

Route::prefix('overlays')->name('overlays.')->group(function () {
    Route::get('start', StartOverlayController::class)
        ->name('start');
    Route::get('end', EndOverlayController::class)
        ->name('end');
    Route::get('main', MainOverlayController::class)
        ->name('main');
    Route::get('break', BreakOverlayController::class)
        ->name('break');
});

Route::get('/auth/twitch/redirect', TwitchRedirectController::class)->name('auth.twitch.redirect');

Route::get('/auth/twitch/callback', TwitchCallbackController::class)->name('auth.twitch.callback');
