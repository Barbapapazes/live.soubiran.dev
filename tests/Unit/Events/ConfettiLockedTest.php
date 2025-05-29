<?php

declare(strict_types=1);

use App\Events\ConfettiLocked;

it('can broadcast confetti locked event', function () {
    $event = new ConfettiLocked();

    expect($event->broadcastOn()->name)->toBe('live');
});
