<?php

declare(strict_types=1);

use App\Actions\DestroyPreset;
use App\Models\Preset;

it('destroys a preset', function () {
    $preset = Preset::factory()->create();

    $this->assertDatabaseCount(Preset::class, 1);

    app()->make(DestroyPreset::class)->handle($preset);

    $this->assertDatabaseCount(Preset::class, 0);
});
