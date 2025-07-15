<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\User;

class CreatePreset
{
    /**
     * @param  array{name: string, color: string, tags: array<array{name: string, color: string}>, start: array<string, string>, break: array<string, string>, end: array<string, string>}  $data
     */
    public function handle(User $user, array $data): void
    {
        $user->presets()->create([
            'name' => $data['name'],
            'data' => [
                'color' => $data['color'],
                'tags' => $data['tags'],
                'start' => $data['start'],
                'break' => $data['break'],
                'end' => $data['end'],
            ],
        ]);
    }
}
