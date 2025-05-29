<?php

declare(strict_types=1);

use Inertia\Testing\AssertableInertia;

it('returns the correct components', function () {
    $this->get(route('overlays.break'))
        ->assertInertia(
            fn (AssertableInertia $page) => $page
                ->component('overlays/break')
        );
});
