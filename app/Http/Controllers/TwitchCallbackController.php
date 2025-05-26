<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Jobs\SubscribeSubscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Laravel\Socialite\Facades\Socialite;

class TwitchCallbackController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): Response
    {
        $twitchUser = Socialite::driver('twitch')->user();

        $user = User::where('twitch_id', $twitchUser->getId())->first();

        if (! $user) {
            $user = User::create([
                'twitch_id' => $twitchUser->getId(),
            ]);
        }

        if (! $user->has_access) {
            return response('You do not have access to this application.', 403);
        }

        SubscribeSubscription::dispatch($user->id, $user->twitch_id, 'channel.chat.message');

        return response('ok', status: 200);
    }
}
