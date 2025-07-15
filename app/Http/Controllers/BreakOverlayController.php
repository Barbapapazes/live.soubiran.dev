<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Preset;
use Illuminate\Http\Request;
use Inertia\Response;

class BreakOverlayController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        $preset = Preset::query()
            ->activated()
            ->first();

        return inertia('overlays/break', [
            'preset' => $preset,
        ]);
    }
}
