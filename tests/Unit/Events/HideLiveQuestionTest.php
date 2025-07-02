<?php

declare(strict_types=1);

use App\Events\HideLiveQuestion;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

beforeEach(function () {});

it('should broadcast now', function () {
    $event = new HideLiveQuestion();

    expect($event)->toBeInstanceOf(ShouldBroadcastNow::class);
});

it('can broadcast hide live question event', function () {
    $event = new HideLiveQuestion();

    expect($event->broadcastOn()->name)->toBe('live');
});
