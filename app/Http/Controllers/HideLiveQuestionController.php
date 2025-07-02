<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\HideLiveQuestion;
use Illuminate\Http\Request;

class HideLiveQuestionController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): void
    {
        event(new HideLiveQuestion());
    }
}
