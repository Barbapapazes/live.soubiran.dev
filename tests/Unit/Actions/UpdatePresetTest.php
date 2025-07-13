<?php

declare(strict_types=1);

use App\Actions\UpdatePreset;
use App\Events\PresetUpdated;
use App\Models\Preset;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    Event::fake();

    $this->preset = Preset::factory()->create();

    $this->name = 'Sample Preset';
    $this->tags = [
        ['name' => 'Tag1', 'color' => '#ff0000'],
        ['name' => 'Tag2', 'color' => '#00ff00'],
    ];
    $this->start = [
        'headline' => 'Sample Headline',
        'title' => 'Sample Title',
    ];
    $this->break = [
        'headline' => 'Break Headline',
        'title' => 'Break Title',
    ];
    $this->end = [
        'headline' => 'End Headline',
        'title' => 'End Title',
        'description' => 'This is the end description.',
    ];
});

it('updates a preset', function () {
    app()->make(UpdatePreset::class)->handle($this->preset, [
        'name' => $this->name,
        'tags' => $this->tags,
        'start' => $this->start,
        'break' => $this->break,
        'end' => $this->end,
    ]);

    $this->assertDatabaseHas(Preset::class, [
        'name' => $this->name,
        'data' => json_encode([
            'tags' => $this->tags,
            'start' => $this->start,
            'break' => $this->break,
            'end' => $this->end,
        ]),
        'user_id' => $this->preset->user_id,
    ]);
});

it('broadcasts preset updated event', function () {
    $this->preset->update(['activated_at' => now()]);

    app()->make(UpdatePreset::class)->handle($this->preset, [
        'name' => $this->name,
        'tags' => $this->tags,
        'start' => $this->start,
        'break' => $this->break,
        'end' => $this->end,
    ]);

    Event::assertDispatched(PresetUpdated::class, function ($event) {
        return $event->preset === $this->preset;
    });
});

it('does not broadcast preset updated event if preset is not activated', function () {
    app()->make(UpdatePreset::class)->handle($this->preset, [
        'name' => $this->name,
        'tags' => $this->tags,
        'start' => $this->start,
        'break' => $this->break,
        'end' => $this->end,
    ]);

    Event::assertNotDispatched(PresetUpdated::class);
});
