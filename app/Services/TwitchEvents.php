<?php

namespace App\Services;

use App\Events\ConfettiExplode;
use App\Events\ConfettiLocked;
use App\Jobs\ReactVideo;
use App\Jobs\ReactYouTubeVideo;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class TwitchEvents
{
    public function confetti(): void
    {
        $lock = Cache::lock('confetti', 10);
        if ($lock->get()) {
            Log::info('Confetti sended');

            event(new ConfettiExplode());
        } else {
            Log::warning('Confetti locked');

            event(new ConfettiLocked());
        }
    }

    public function react(?string $url): void
    {
        if (empty($url)) {
            Log::warning('React command received without URL');
            return;
        }

        if (!$this->isYouTubeUrl($url)) {
            Log::warning('React command received with invalid URL', [
                'url' => $url,
            ]);
            return;
        }

        ReactYouTubeVideo::dispatch($url);

        Log::info('React to video', [
            'url' => $url,
        ]);
    }

    private function isYouTubeUrl(string $url): bool
    {
        return preg_match('/^(https?:\/\/)?(www\.)?(youtube\.com|youtu\.be)\/.+$/', $url);
    }
}
