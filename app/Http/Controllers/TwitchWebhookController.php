<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Events\ConfettiExplode;
use App\Http\Requests\TwitchWebhookRequest;
use App\Jobs\SubscribeSubscription;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class TwitchWebhookController extends Controller
{
    public function __invoke(TwitchWebhookRequest $request): Response
    {
        $messageType = $request->header('Twitch-Eventsub-Message-Type');

        if ($messageType === 'webhook_callback_verification' && $request->has('challenge')) {
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
                $lock = Cache::lock('confetti', 10);
                if ($lock->get()) {
                    event(new ConfettiExplode());
                }
            }

            return response()->noContent(200);
        }

        if ($messageType === 'revocation') {
            $user = User::where('twitch_id', $request->input('subscription.condition.broadcaster_user_id'))->sole();

            $user->subscriptions()
                ->where('subscription_id', $request->input('subscription.id'))
                ->delete();

            SubscribeSubscription::dispatch($user->id, $user->twitch_id, $request->input('subscription.type'));
        }

        return response()->noContent(200);
    }
}
