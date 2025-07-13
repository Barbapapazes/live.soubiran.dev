<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Preset;
use Inertia\Response;

class EndOverlayController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        $preset = Preset::query()
            ->activated()
            ->first();

        return inertia('overlays/end', [
            'preset' => fn () => $preset,
        ]);
    }
}
