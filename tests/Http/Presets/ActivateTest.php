<?php

declare(strict_types=1);

use App\Events\PresetActivated;
use App\Models\Preset;
use App\Models\User;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    Event::fake();

    $this->user = User::factory()->hasAccess()->create();
    $this->preset = Preset::factory()->for($this->user)->create();
});

it('requires authentication', function () {
    $this
        ->post(route('presets.activate', $this->preset))
        ->assertRedirect('/login');
});

it('requires access', function () {
    $this
        ->actingAs(User::factory()->create())
        ->post(route('presets.activate', $this->preset))
        ->assertForbidden();
});

it('activates the preset', function () {
    $this
        ->actingAs($this->user)
        ->post(route('presets.activate', $this->preset))
        ->assertOk();

    $this->assertDatabaseHas(Preset::class, [
        'id' => $this->preset->id,
        'user_id' => $this->user->id,
        'activated_at' => now(),
    ]);
});

it('broadcasts preset activated event', function () {
    $this
        ->actingAs($this->user)
        ->post(route('presets.activate', $this->preset))
        ->assertOk();

    Event::assertDispatched(PresetActivated::class, function (PresetActivated $event) {
        return $event->preset->id === $this->preset->id;
    });
});

it('deactivates the preset already activated', function () {
    $preset = Preset::factory()->activated()->for($this->user)->create();

    $this
        ->actingAs($this->user)
        ->post(route('presets.activate', $this->preset))
        ->assertOk();

    $this->assertDatabaseMissing(Preset::class, [
        'id' => $preset->id,
        'activated_at' => now(),
    ]);
});

it('cannot activate preset from another user', function () {
    $this
        ->actingAs(User::factory()->hasAccess()->create())
        ->post(route('presets.activate', $this->preset))
        ->assertForbidden();
});
