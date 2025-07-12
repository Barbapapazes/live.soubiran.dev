<?php

declare(strict_types=1);

use App\Http\Middleware\EnsureHasAccess;
use App\Models\User;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    $this->user = User::factory()->create();
});

it('allows access when the user has access', function () {
    $this->user->update(['has_access' => true]);

    Route::middleware(EnsureHasAccess::class)
        ->get('/test', function () {
            return 'OK';
        });

    $this->actingAs($this->user)->get('/test')->assertOk();
});

it('returns 403 when the user does not have access', function () {
    $this->user->update(['has_access' => false]);

    Route::middleware(EnsureHasAccess::class)
        ->get('/test', function () {
            return 'OK';
        });

    $this->actingAs($this->user)->get('/test')->assertForbidden();
});
