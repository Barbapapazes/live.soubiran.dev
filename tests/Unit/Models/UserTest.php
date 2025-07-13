<?php

declare(strict_types=1);

use App\Models\Preset;
use App\Models\Subscription;
use App\Models\User;

test('to array', function () {
    $user = User::factory()->create()->fresh();

    expect(array_keys($user->toArray()))
        ->toEqual([
            'id',
            'twitch_id',
            'has_access',
            'created_at',
            'updated_at',
        ]);
});

test('relations', function () {
    $user = User::factory()
        ->hasSubscriptions()
        ->hasPresets()
        ->create();

    expect($user->subscriptions)->each->toBeInstanceOf(Subscription::class)
        ->and($user->presets)->each->toBeInstanceOf(Preset::class);
});
