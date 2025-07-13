<?php

declare(strict_types=1);

use App\Actions\ActivatePreset;
use App\Events\PresetActivated;
use App\Models\Preset;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    Event::fake();

    $this->preset = Preset::factory()->create();
});

it('activates a preset', function () {
    app()->make(ActivatePreset::class)->handle($this->preset);

    $this->assertDatabaseHas(Preset::class, [
        'id' => $this->preset->id,
        'activated_at' => now()->toDateTimeString(),
    ]);
});

it('deactivates all presets before activating a new one', function () {
    $presets = Preset::factory()->count(3)->activated()->create();

    app()->make(ActivatePreset::class)->handle($this->preset);

    foreach ($presets as $deactivatedPreset) {
        $this->assertDatabaseMissing(Preset::class, [
            'id' => $deactivatedPreset->id,
            'activated_at' => now()->toDateTimeString(),
        ]);
    }
});

it('broadcasts an event when a preset is activated', function () {
    app()->make(ActivatePreset::class)->handle($this->preset);

    Event::assertDispatched(PresetActivated::class, function ($event) {
        return $event->preset === $this->preset;
    });
});
