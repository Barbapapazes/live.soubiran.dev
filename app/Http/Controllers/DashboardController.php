<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Preset;
use Inertia\Response;

class DashboardController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return inertia('dashboard', [
            'presets' => fn () => Preset::all(),
        ]);
    }
}
