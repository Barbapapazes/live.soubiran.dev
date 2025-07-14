<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Preset;

class DestroyPreset
{
    public function handle(Preset $preset): void
    {
        $preset->delete();
    }
}
