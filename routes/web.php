<?php

declare(strict_types=1);

use App\Http\Controllers\BreakOverlayController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EndOverlayController;
use App\Http\Controllers\HideLiveQuestionController;
use App\Http\Controllers\MainOverlayController;
use App\Http\Controllers\ShowLiveQuestionController;
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

Route::middleware('auth')->group(function () {
    Route::get('dashboard', DashboardController::class)
        ->name('dashboard');

    Route::post('live-question', ShowLiveQuestionController::class)
        ->name('live-question.show');
    Route::delete('live-question', HideLiveQuestionController::class)
        ->name('live-question.hide');

});

Route::redirect('login', 'auth/twitch/redirect')
    ->name('login');
Route::get('auth/twitch/redirect', TwitchRedirectController::class)->name('auth.twitch.redirect');
Route::get('auth/twitch/callback', TwitchCallbackController::class)->name('auth.twitch.callback');
