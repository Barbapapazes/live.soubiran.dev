<?php

declare(strict_types=1);

namespace App\Http\Requests;

use App\Services\Twitch;
use Illuminate\Foundation\Http\FormRequest;

class TwitchWebhookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(Twitch $twitch): bool
    {
        $signature = $this->header('Twitch-Eventsub-Message-Signature');

        $messageId = $this->header('Twitch-Eventsub-Message-Id');
        $timestamp = $this->header('Twitch-Eventsub-Message-Timestamp');
        $body = $this->getContent();

        if (! $signature || ! $messageId || ! $timestamp || ! $body) {
            return false;
        }

        return $twitch->isValidTwitchEvent($signature, $messageId, $timestamp, $body);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
        ];
    }
}
