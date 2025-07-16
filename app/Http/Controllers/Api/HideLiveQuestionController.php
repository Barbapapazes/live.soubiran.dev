<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Events\HideLiveQuestion;
use Illuminate\Http\Request;

class HideLiveQuestionController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): void
    {
        if ($request->input('key') !== config('services.confetti.key')) {
            abort(403, 'Invalid key provided');
        }

        event(new HideLiveQuestion());
    }
}
