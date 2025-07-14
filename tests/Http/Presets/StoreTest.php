<?php

declare(strict_types=1);

use App\Models\Preset;
use App\Models\User;

beforeEach(function () {
    $this->user = User::factory()->hasAccess()->create();
});

it('requires authentication', function () {
    $this
        ->post(route('presets.store'))
        ->assertRedirect('/login');
});

it('requires access', function () {
    $this
        ->actingAs(User::factory()->create())
        ->post(route('presets.store'))
        ->assertForbidden();
});

it('creates a preset', function () {
    $payload = [
        'name' => 'Test Preset',
        'tags' => [
            ['name' => 'Tag1', 'color' => '#ff0000'],
            ['name' => 'Tag2', 'color' => '#00ff00'],
        ],
        'start' => [
            'headline' => 'This is a test preset',
            'title' => 'Test Title',
        ],
        'break' => [
            'headline' => 'Break Headline',
            'title' => 'Break Title',
        ],
        'end' => [
            'headline' => 'End Headline',
            'title' => 'End Title',
            'description' => 'This is the end description.',
        ],
    ];

    $this
        ->actingAs($this->user)
        ->post(route('presets.store'), $payload)
        ->assertOk();

    $this->assertDatabaseHas(Preset::class, [
        'name' => 'Test Preset',
        'data' => json_encode([
            'tags' => [
                ['name' => 'Tag1', 'color' => '#ff0000'],
                ['name' => 'Tag2', 'color' => '#00ff00'],
            ],
            'start' => [
                'headline' => 'This is a test preset',
                'title' => 'Test Title',

            ],
            'break' => [
                'headline' => 'Break Headline',
                'title' => 'Break Title',
            ],
            'end' => [
                'headline' => 'End Headline',
                'title' => 'End Title',
                'description' => 'This is the end description.',
            ],
        ]),
    ]);
});

it('validates data', function (array $payload, array $error) {
    $this
        ->actingAs($this->user)
        ->post(route('presets.store'), $payload)
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
