<?php

declare(strict_types=1);

namespace App\Actions;

use App\Events\PresetActivated;
use App\Models\Preset;

class ActivatePreset
{
    public function __construct(
        private readonly DeactivatePresets $deactivatePresets
    ) {}

    public function handle(Preset $preset): void
    {
        $this->deactivatePresets->handle();

        $preset->activate();

        event(new PresetActivated($preset));
    }
}
