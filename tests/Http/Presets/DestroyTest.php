<?php

declare(strict_types=1);

use App\Models\Preset;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->hasAccess()->create();
    $this->preset = Preset::factory()->for($this->user)->create();
});

it('requires authentication', function () {
    $this
        ->delete(route('presets.destroy', $this->preset))
        ->assertRedirect('/login');
});

it('requires access', function () {
    $this
        ->actingAs(User::factory()->create())
        ->delete(route('presets.destroy', $this->preset))
        ->assertForbidden();
});

it('deletes a preset', function () {
    $this
        ->actingAs($this->user)
        ->delete(route('presets.destroy', $this->preset))
        ->assertOk();

    $this->assertDatabaseMissing('presets', [
        'id' => $this->preset->id,
    ]);
});

it('can only delete its own presets', function () {
    $anotherPreset = Preset::factory()->create();

    $this
        ->actingAs($this->user)
        ->delete(route('presets.destroy', $anotherPreset))
        ->assertForbidden();
});
