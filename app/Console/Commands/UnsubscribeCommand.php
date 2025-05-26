<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Services\Twitch;
use Illuminate\Console\Command;

class UnsubscribeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:unsubscribe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Unsubscribe from all Twitch events for the application';

    /**
     * Execute the console command.
     */
    public function handle(Twitch $twitch): void
    {
        $subscriptions = $twitch->getSubscriptions();

        foreach ($subscriptions as $subscription) {
            $twitch->unsubscribe($subscription['id']);

            Subscription::where('subscription_id', $subscription['id'])->delete();
        }

        $this->info('Unsubscribed from all Twitch events successfully.');
    }
}
