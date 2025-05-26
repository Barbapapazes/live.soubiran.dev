<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\ConfettiExplode;
use App\Http\Requests\TwitchWebhookRequest;
use App\Jobs\SubscribeSubscription;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TwitchWebhookController extends Controller
{
    public function __invoke(TwitchWebhookRequest $request): Response
    {
        $messageType = $request->header('Twitch-Eventsub-Message-Type');

        if ($messageType === 'webhook_callback_verification' && $request->has('challenge')) {
            Log::info('Received Twitch webhook callback verification', [
                'challenge' => $request->input('challenge'),
                'subscription_id' => $request->input('subscription.id'),
            ]);

            User::where('twitch_id', $request->input('subscription.condition.broadcaster_user_id'))->sole()
                ->subscriptions()
                ->create([
                    'subscription_id' => $request->input('subscription.id'),
                    'event' => $request->input('subscription.type'),
                ]);

            return response($request->input('challenge'), 200)->header('Content-Type', 'text/plain');
        }

        if ($messageType === 'notification' && $request->has('event')) {
            if (Str::startsWith($request->input('event.message.text'), '!confetti')) {
                Log::info('Confetti command received');

                $lock = Cache::lock('confetti', 10);
                if ($lock->get()) {
                    event(new ConfettiExplode());
                } else {
                    Log::warning('Confetti command ignored due to existing lock');
                }
            }

            return response()->noContent(200);
        }

        if ($messageType === 'revocation') {
            Log::info('Received Twitch webhook subscription revocation', [
                'subscription_id' => $request->input('subscription.id'),
                'event' => $request->input('subscription.type'),
            ]);

            $user = User::where('twitch_id', $request->input('subscription.condition.broadcaster_user_id'))->sole();

            $user->subscriptions()
                ->where('subscription_id', $request->input('subscription.id'))
                ->delete();

            SubscribeSubscription::dispatch($user->id, $user->twitch_id, $request->input('subscription.type'));
        }

        return response()->noContent(200);
    }
}
