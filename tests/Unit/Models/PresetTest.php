<?php

declare(strict_types=1);

use App\Models\Preset;
use App\Models\User;

test('to array', function () {
    $preset = Preset::factory()->create()->fresh();

    expect(array_keys($preset->toArray()))
        ->toEqual([
            'id',
            'name',
            'data',
            'activated_at',
            'user_id',
            'created_at',
            'updated_at',
        ]);
});

test('relations', function () {
    $preset = Preset::factory()
        ->hasUser()
        ->create();

    expect($preset->user)->toBeInstanceOf(User::class);
});

test('activate', function () {
    $preset = Preset::factory()->create();

    expect($preset->isActivated())->toBeFalse();

    $preset->activate();

    expect($preset->isActivated())->toBeTrue();
});
