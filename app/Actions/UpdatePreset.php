<?php

declare(strict_types=1);

namespace App\Actions;

use App\Events\PresetUpdated;
use App\Models\Preset;

class UpdatePreset
{
    /**
     * @param  array{name: string, color: string, tags: array<array{name: string, color: string}>, start: array<string, string>, break: array<string, string>, end: array<string, string>}  $data
     */
    public function handle(Preset $preset, array $data): void
    {
        $preset->update([
            'name' => $data['name'],
            'data' => [
                'color' => $data['color'],
                'tags' => $data['tags'],
                'start' => $data['start'],
                'break' => $data['break'],
                'end' => $data['end'],
            ],
        ]);

        if ($preset->isActivated()) {
            event(new PresetUpdated($preset));
        }
    }
}
