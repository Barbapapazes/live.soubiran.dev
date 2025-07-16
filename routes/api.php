<?php

declare(strict_types=1);

use App\Http\Controllers\Api\CelebrateController;
use App\Http\Controllers\Api\HideLiveQuestionController;
use App\Http\Controllers\TwitchWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('webhook/twitch', TwitchWebhookController::class)
    ->name('webhook.twitch');

Route::get('celebrate', CelebrateController::class)
    ->name('api.celebrate');
Route::get('live-question/hide', HideLiveQuestionController::class)
    ->name('api.live-question.hide');
