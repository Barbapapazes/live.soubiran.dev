<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Preset;

class DeactivatePresets
{
    public function handle(): void
    {
        Preset::query()
            ->activated()
            ->update(['activated_at' => null]);
    }
}
