<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Support\Collection;

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
    $user = User::factory()->hasSubscriptions()->create();

    expect($user->subscriptions)->toBeInstanceOf(Collection::class);
});
