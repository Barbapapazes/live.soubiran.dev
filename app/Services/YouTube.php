<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class YouTube
{
    /**
     * Get video details (title, date, thumbnail) from a YouTube video ID.
     *
     * @param string $videoId
     * @return array|null
     */
    public function getVideoDetails(string $videoId): ?array
    {
        $response = Http::get('https://www.googleapis.com/youtube/v3/videos', [
            'id' => $videoId,
            'key' => config('services.youtube.key'),
            'part' => 'snippet',
        ]);

        if (!$response->ok() || empty($response['items'][0]['snippet'])) {
            return null;
        }

        $snippet = $response['items'][0]['snippet'];

        return [
            'title' => $snippet['title'],
            'date' => $snippet['publishedAt'],
            'thumbnail' => $snippet['thumbnails']['high']['url'] ?? null,
        ];
    }
}
