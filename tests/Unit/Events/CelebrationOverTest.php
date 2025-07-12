<?php

declare(strict_types=1);

use App\Events\CelebrationOver;

it('can broadcast celebration over event', function () {
    $event = new CelebrationOver();

    expect($event->broadcastOn()->name)->toBe('live');
});
