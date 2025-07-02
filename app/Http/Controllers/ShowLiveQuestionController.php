<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\ShowLiveQuestion;
use Illuminate\Http\Request;

class ShowLiveQuestionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): void
    {
        event(new ShowLiveQuestion(
            $request->input('question'),
            $request->input('username'),
            $request->input('color')
        ));
    }
}
