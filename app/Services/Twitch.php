<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Twitch
{
    /**
     * Verify if the request is a valid Twitch event
     */
    public function isValidTwitchEvent(string $signature, string $messageId, string $timestamp, string $body): bool
    {
        $expectedSignature = 'sha256='.hash_hmac('sha256', $messageId.$timestamp.$body, (string) config('services.twitch.secret'));

        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Get App Access Token
     */
    public function getAppAccessToken(): string
    {
        if ($value = Cache::get('twitch_app_access_token')) {
            return $value;
        }

        $response = Http::asForm()->post('https://id.twitch.tv/oauth2/token', [
            'client_id' => config('services.twitch.client_id'),
            'client_secret' => config('services.twitch.client_secret'),
            'grant_type' => 'client_credentials',
        ]);

        if ($response->failed()) {
            throw new Exception('Failed to get app access token from Twitch');
        }

        Cache::put(
            'twitch_app_access_token',
            $response->json('access_token'),
            now()->addSeconds($response->json('expires_in'))
        );

        return $response->json('access_token');
    }

    /**
     * Subscribe to a Twitch event
     */
    public function subscribe(string $broadcasterUserId, string $event): void
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->getAppAccessToken(),
            'Client-Id' => config('services.twitch.client_id'),
            'Content-Type' => 'application/json',
        ])->post('https://api.twitch.tv/helix/eventsub/subscriptions', [
            'type' => $event,
            'version' => '1',
            'condition' => [
                'broadcaster_user_id' => $broadcasterUserId,
                'user_id' => $broadcasterUserId,
            ],
            'transport' => [
                'method' => 'webhook',
                'callback' => config('services.twitch.callback_url').'/api/webhook/twitch',
                'secret' => config('services.twitch.secret'),
            ],
        ]);

        if ($response->failed()) {
            Log::critical('Failed to subscribe to Twitch event', [
                'broadcaster_user_id' => $broadcasterUserId,
                'event' => $event,
                'response' => $response->json(),
            ]);

            throw new Exception('Failed to subscribe to Twitch event');
        }
    }

    /**
     * Get the list of Twitch subscriptions
     *
     * @return array<int, array<string, mixed>>
     */
    public function getSubscriptions(): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->getAppAccessToken(),
            'Client-Id' => config('services.twitch.client_id'),
            'Content-Type' => 'application/json',
        ])->get('https://api.twitch.tv/helix/eventsub/subscriptions');

        if ($response->failed()) {
            throw new Exception('Failed to get Twitch subscriptions');
        }

        return $response->json('data');
    }

    /**
     * Unsubscribe from a Twitch event
     */
    public function unsubscribe(string $subscriptionId): void
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->getAppAccessToken(),
            'Client-Id' => config('services.twitch.client_id'),
            'Content-Type' => 'application/json',
        ])->delete('https://api.twitch.tv/helix/eventsub/subscriptions', [
            'id' => $subscriptionId,
        ]);

        if ($response->failed()) {
            throw new Exception('Failed to unsubscribe from Twitch event');
        }
    }
}
