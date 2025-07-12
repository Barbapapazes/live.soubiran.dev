<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Actions\Celebrate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CelebrateController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Celebrate $celebrate): void
    {
        if ($request->input('key') !== config('services.confetti.key')) {
            abort(403, 'Invalid key provided');
        }

        Log::info('Celebrate triggered via API');

        $celebrate->handle();
    }
}
