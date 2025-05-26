<?php

declare(strict_types=1);

use App\Http\Controllers\TwitchWebhookController;
use Illuminate\Support\Facades\Route;

Route::post('/webhook/twitch', TwitchWebhookController::class)
    ->name('webhook.twitch');
