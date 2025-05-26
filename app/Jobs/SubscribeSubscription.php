<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\Subscription;
use App\Services\Twitch;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SubscribeSubscription implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public readonly int $userId,
        public readonly string $broadcasterUserId,
        public readonly string $event,
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(
        Twitch $twitch,
    ): void {
        if (Subscription::where('user_id', $this->userId)
            ->where('event', $this->event)
            ->exists()
        ) {
            return;
        }

        $twitch->subscribe(
            $this->broadcasterUserId,
            $this->event,
        );
    }
}
