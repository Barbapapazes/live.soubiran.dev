<?php

declare(strict_types=1);

use App\Events\ConfettiExplode;
use Illuminate\Broadcasting\Channel;

it('can broadcast confetti explode event', function () {
    $event = new ConfettiExplode();

    expect($event->broadcastOn()->name)
        ->toBe(new Channel('live')->name);
});
