<?php

declare(strict_types=1);

use App\Models\Subscription;
use App\Models\User;

test('to array', function () {
    $subscription = Subscription::factory()->create()->fresh();

    expect(array_keys($subscription->toArray()))
        ->toEqual([
            'id',
            'user_id',
            'subscription_id',
            'event',
            'created_at',
            'updated_at',
        ]);
});

test('relations', function () {
    $subscription = Subscription::factory()->hasUser()->create();

    expect($subscription->user)->toBeInstanceOf(User::class);
});
