<?php

declare(strict_types=1);

use App\Models\Preset;
use Inertia\Testing\AssertableInertia;

it('returns the correct component', function () {
    $this->get(route('overlays.start'))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('overlays/start')
        );
});

it('returns the activated preset', function () {
    $preset = Preset::factory()->activated()->create();

    $this->get(route('overlays.start'))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->has('preset', fn (AssertableInertia $presetPage) => $presetPage
                ->where('id', $preset->id)
                ->where('name', $preset->name)
                ->where('data', $preset->data)
                ->where('activated_at', $preset->activated_at->toIsoString())
                ->etc()
            )
        );
});
