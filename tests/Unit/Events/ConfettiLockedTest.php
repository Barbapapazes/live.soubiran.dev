<?php

declare(strict_types=1);

use App\Events\ConfettiLocked;
use Illuminate\Broadcasting\Channel;

it('can broadcast confetti locked event', function () {
    $event = new ConfettiLocked();

    expect($event->broadcastOn()->name)
        ->toBe(new Channel('live')->name);
});
