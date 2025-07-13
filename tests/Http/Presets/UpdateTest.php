<?php

declare(strict_types=1);

use App\Events\PresetUpdated;
use App\Models\Preset;
use App\Models\User;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    Event::fake();

    $this->user = User::factory()->hasAccess()->create();
    $this->preset = Preset::factory()->for($this->user)->create();

    $this->payload = [
        'name' => 'Updated Preset',
        'tags' => [
            ['name' => 'Tag1', 'color' => '#ff0000'],
            ['name' => 'Tag2', 'color' => '#00ff00'],
        ],
        'start' => [
            'headline' => 'This is an updated test preset',
            'title' => 'Updated Title',
        ],
        'break' => [
            'headline' => 'Break Headline',
            'title' => 'Break Title',
        ],
        'end' => [
            'headline' => 'End Headline',
            'title' => 'End Title',
            'description' => 'This is the updated description.',
        ],
    ];
});

it('requires authentication', function () {
    $this
        ->put(route('presets.update', $this->preset))
        ->assertRedirect('/login');
});

it('requires access', function () {
    $this
        ->actingAs(User::factory()->create())
        ->put(route('presets.update', $this->preset))
        ->assertForbidden();
});

it('updates a preset', function () {
    $this
        ->actingAs($this->user)
        ->put(route('presets.update', $this->preset), $this->payload)
        ->assertOk();

    $this->assertDatabaseHas(Preset::class, [
        'name' => 'Updated Preset',
        'data' => json_encode([
            'tags' => [
                ['name' => 'Tag1', 'color' => '#ff0000'],
                ['name' => 'Tag2', 'color' => '#00ff00'],
            ],
            'start' => [
                'headline' => 'This is an updated test preset',
                'title' => 'Updated Title',
            ],
            'break' => [
                'headline' => 'Break Headline',
                'title' => 'Break Title',
            ],
            'end' => [
                'headline' => 'End Headline',
                'title' => 'End Title',
                'description' => 'This is the updated description.',
            ],
        ]),
    ]);
});

it('validates data', function (array $payload, array $error) {
    $this
        ->actingAs($this->user)
        ->put(route('presets.update', $this->preset), $payload)
        ->assertSessionHasErrors($error);
})
    ->with([
        // name validation
        [['name' => ''], ['name' => 'The name is required.']],
        [['name' => false], ['name' => 'The name must be a string.']],
        [['name' => str_repeat('a', 101)], ['name' => 'The name must not be greater than 100 characters.']],
        // tags validation
        [['tags' => 'not-an-array'], ['tags' => 'The tags must be an array.']],
        [['tags' => []], ['tags' => 'The tags are required.']],
        // tags.* validation
        [['tags' => [['name' => '', 'color' => '#ffffff']]], ['tags.0.name' => 'The tag name is required.']],
        [['tags' => [['name' => str_repeat('a', 13), 'color' => '#ffffff']]], ['tags.0.name' => 'The tag name must not be greater than 12 characters.']],
        [['tags' => [['name' => 'Tag', 'color' => 'not-a-hex']]], ['tags.0.color' => 'The tag color must be a valid hexadecimal color.']],
        [['tags' => [['color' => '#ffffff']]], ['tags.0.name' => 'The tag name is required.']],
        [['tags' => [['name' => 'Tag']]], ['tags.0.color' => 'The tag color is required.']],
        // start.headline validation
        [['start' => ['headline' => '']], ['start.headline' => 'The headline is required.']],
        [['start' => ['headline' => false]], ['start.headline' => 'The headline must be a string.']],
        [['start' => ['headline' => str_repeat('a', 101)]], ['start.headline' => 'The headline must not be greater than 100 characters.']],
        // start.title validation
        [['start' => ['title' => '']], ['start.title' => 'The title is required.']],
        [['start' => ['title' => false]], ['start.title' => 'The title must be a string.']],
        [['start' => ['title' => str_repeat('a', 101)]], ['start.title' => 'The title must not be greater than 100 characters.']],
        // break.headline validation
        [['break' => ['headline' => '']], ['break.headline' => 'The headline is required.']],
        [['break' => ['headline' => false]], ['break.headline' => 'The headline must be a string.']],
        [['break' => ['headline' => str_repeat('a', 101)]], ['break.headline' => 'The headline must not be greater than 100 characters.']],
        // break.title validation
        [['break' => ['title' => '']], ['break.title' => 'The title is required.']],
        [['break' => ['title' => false]], ['break.title' => 'The title must be a string.']],
        [['break' => ['title' => str_repeat('a', 101)]], ['break.title' => 'The title must not be greater than 100 characters.']],
        // end.headline validation
        [['end' => ['headline' => '']], ['end.headline' => 'The headline is required.']],
        [['end' => ['headline' => false]], ['end.headline' => 'The headline must be a string.']],
        [['end' => ['headline' => str_repeat('a', 101)]], ['end.headline' => 'The headline must not be greater than 100 characters.']],
        // end.title validation
        [['end' => ['title' => '']], ['end.title' => 'The title is required.']],
        [['end' => ['title' => false]], ['end.title' => 'The title must be a string.']],
        [['end' => ['title' => str_repeat('a', 101)]], ['end.title' => 'The title must not be greater than 100 characters.']],
        // end.description validation
        [['end' => ['description' => '']], ['end.description' => 'The description is required.']],
        [['end' => ['description' => false]], ['end.description' => 'The description must be a string.']],
        [['end' => ['description' => str_repeat('a', 256)]], ['end.description' => 'The description must not be greater than 255 characters.']],
    ]);

it('can only update its own presets', function () {
    $anotherPreset = Preset::factory()->create();

    $this
        ->actingAs($this->user)
        ->put(route('presets.update', $anotherPreset), $this->payload)
        ->assertForbidden();
});

it('broadcasts preset updated event', function () {
    $this->preset->update(['activated_at' => now()]);

    $this
        ->actingAs($this->user)
        ->put(route('presets.update', $this->preset), $this->payload)
        ->assertOk();

    Event::assertDispatched(PresetUpdated::class, function ($event) {
        return $event->preset->id === $this->preset->id &&
            $event->preset->name === $this->payload['name'] &&
            $event->preset->data['start'] === $this->payload['start'] &&
            $event->preset->data['end'] === $this->payload['end'];
    });
});

it('does not broadcast preset updated event if preset is not activated', function () {
    $this
        ->actingAs($this->user)
        ->put(route('presets.update', $this->preset), $this->payload)
        ->assertOk();

    Event::assertNotDispatched(PresetUpdated::class);
});
