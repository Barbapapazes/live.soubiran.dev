<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\ConfettiExplode;
use App\Http\Requests\ExplodeConfettiRequest;
use Illuminate\Support\Facades\Log;

class ExplodeConfettiController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(ExplodeConfettiRequest $request): void
    {
        Log::info('Confetti explosion triggered via GET request');

        event(new ConfettiExplode());
    }
}
