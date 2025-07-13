<?php

declare(strict_types=1);

use App\Actions\DeactivatePresets;
use App\Models\Preset;

it('deactivates all presets', function () {
    Preset::factory()->count(3)->activated()->create();

    app()->make(DeactivatePresets::class)->handle();

    $this->assertDatabaseMissing(Preset::class, [
        'activated_at' => now()->toDateTimeString(),
    ]);
});
