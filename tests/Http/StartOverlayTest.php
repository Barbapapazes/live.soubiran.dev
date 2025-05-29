<?php

declare(strict_types=1);

use Inertia\Testing\AssertableInertia;

it('returns the correct component', function () {
    $this->get(route('overlays.start'))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('overlays/start')
        );
});
