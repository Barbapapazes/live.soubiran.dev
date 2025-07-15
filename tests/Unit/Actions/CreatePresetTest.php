<?php

declare(strict_types=1);

use App\Actions\CreatePreset;
use App\Models\Preset;
use App\Models\User;

it('creates a preset', function () {
    $user = User::factory()->create();
    $name = 'Sample Preset';
    $color = '#ff5733';
    $tags = [
        ['name' => 'Tag1', 'color' => '#ff0000'],
        ['name' => 'Tag2', 'color' => '#00ff00'],
    ];
    $start = [
        'headline' => 'Sample Headline',
        'title' => 'Sample Title',
    ];
    $break = [
        'headline' => 'Break Headline',
        'title' => 'Break Title',
    ];
    $end = [
        'headline' => 'End Headline',
        'title' => 'End Title',
        'description' => 'This is the end description.',
    ];

    app()->make(CreatePreset::class)->handle($user, [
        'name' => $name,
        'color' => $color,
        'tags' => $tags,
        'start' => $start,
        'break' => $break,
        'end' => $end,
    ]);

    $this->assertDatabaseHas(Preset::class, [
        'name' => $name,
        'data' => json_encode([
            'color' => $color,
            'tags' => $tags,
            'start' => $start,
            'break' => $break,
            'end' => $end,
        ]),
        'user_id' => $user->id,
    ]);
});
