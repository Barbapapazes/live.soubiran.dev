<?php

declare(strict_types=1);

namespace App\Actions;

use App\Events\CelebrationOver;

class Celebrate
{
    public function handle(): void
    {
        event(new CelebrationOver());
    }
}
