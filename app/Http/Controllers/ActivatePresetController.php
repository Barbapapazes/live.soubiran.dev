<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\ActivatePreset;
use App\Models\Preset;
use Illuminate\Support\Facades\Gate;

class ActivatePresetController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Preset $preset, ActivatePreset $activatePreset): void
    {
        Gate::authorize('update', $preset);

        $activatePreset->handle($preset);
    }
}
