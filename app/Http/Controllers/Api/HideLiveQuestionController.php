<?php

namespace App\Http\Controllers\Api;

use App\Events\HideLiveQuestion;
use Illuminate\Http\Request;

class HideLiveQuestionController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if ($request->input('key') !== config('services.confetti.key')) {
            abort(403, 'Invalid key provided');
        }

        event(new HideLiveQuestion());
    }
}
