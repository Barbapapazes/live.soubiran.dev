<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Soubiran
{
    /**
     * Send an event to the Soubiran webhook URL with GitHub-style HMAC signature.
     *
     * @param string $event Event name/type
     * @param array $payload Data to send
     * @return \Illuminate\Http\Client\Response
     */
    public function sendEvent(string $event, array $payload)
    {
        $url = config('services.soubiran.webhook_url');
        $secret = config('services.soubiran.webhook_secret');
        $signature = 'sha256=' . hash_hmac('sha256', json_encode($payload), $secret);

        Log::debug('Sending Soubiran event', [
            'event' => $event,
            'payload' => $payload,
            'signature' => $signature,
        ]);

        return Http::withHeaders([
            'X-Soubiran-Signature-256' => $signature,
            'X-Soubiran-Event' => $event,
            'Content-Type' => 'application/json',
        ])->post($url, $payload);
    }
}
