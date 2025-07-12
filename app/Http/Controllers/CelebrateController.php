<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Celebrate;

class CelebrateController
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Celebrate $celebrate): void
    {
        $celebrate->handle();
    }
}
