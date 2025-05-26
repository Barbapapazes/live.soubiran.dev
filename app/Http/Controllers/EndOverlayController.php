<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Response;

class EndOverlayController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return inertia('overlays/end');
    }
}
