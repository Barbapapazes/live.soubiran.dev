<?php

declare(strict_types=1);

use App\Events\PresetUpdated;
use App\Models\Preset;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

beforeEach(function () {
    $this->preset = Preset::factory()->create();
});

it('broadcasts preset updated event', function () {
    $event = new PresetUpdated($this->preset);

    expect($event->broadcastOn()->name)->toBe('live');
});

it('broadcasts now', function () {
    $event = new PresetUpdated($this->preset);

    expect($event)->toBeInstanceOf(ShouldBroadcastNow::class);
});

it('broadcasts preset data', function () {
    $event = new PresetUpdated($this->preset);

    expect($event->preset)->toBeInstanceOf(Preset::class)
        ->and($event->preset->id)->toBe($this->preset->id)
        ->and($event->preset->name)->toBe($this->preset->name)
        ->and($event->preset->data)->toBe($this->preset->data);
});
