<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\Celebrate;
use App\Events\ReceivedMessage;
use App\Http\Requests\TwitchWebhookRequest;
use App\Jobs\SubscribeSubscription;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TwitchWebhookController
{
    public function __invoke(TwitchWebhookRequest $request, Celebrate $celebrate): Response
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
            event(new ReceivedMessage(
                $request->input('event.message.text'),
                $request->input('event.chatter_user_name'),
                $request->input('event.color')
            ));

            if (
                Str::startsWith($request->input('event.message.text'), '!confetti') ||
                Str::startsWith($request->input('event.message.text'), '!confettis') ||
                Str::startsWith($request->input('event.message.text'), '!celebrate')
            ) {
                Log::info('Celebrate command received');

                $celebrate->handle();
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
