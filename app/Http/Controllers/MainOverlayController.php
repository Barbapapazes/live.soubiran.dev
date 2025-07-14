<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Preset;
use Inertia\Response;

class MainOverlayController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        $preset = Preset::query()
            ->activated()
            ->first();

        return inertia('overlays/main', [
            'preset' => $preset,
        ]);
    }
}
