<?php

declare(strict_types=1);

use App\Actions\Celebrate;
use App\Events\CelebrationOver;
use Illuminate\Support\Facades\Event;

it('celebrates', function () {
    Event::fake();

    app()->make(Celebrate::class)->handle();

    Event::assertDispatched(CelebrationOver::class);
});
