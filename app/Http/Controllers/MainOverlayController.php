<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Inertia\Response;

class MainOverlayController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        return inertia('overlays/main');
    }
}
