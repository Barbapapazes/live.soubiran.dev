<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\HideLiveQuestion;

class HideLiveQuestionController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): void
    {
        event(new HideLiveQuestion());
    }
}
