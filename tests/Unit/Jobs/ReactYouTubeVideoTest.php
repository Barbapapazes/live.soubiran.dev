<?php

use App\Jobs\ReactYouTubeVideo;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Config::set('services.youtube.key', 'test_youtube_api_key');

    Config::set('services.soubiran.webhook_url', 'https://api.soubiran.dev/webhook');
    Config::set('services.soubiran.webhook_secret', 'test_soubiran_secret');
});

it('extracts video ID from various YouTube URL formats', function ($url, $expectedId) {
    Http::fake(
        [
            'https://www.googleapis.com/youtube/v3/videos*' => Http::response([
                'items' => [[
                    'snippet' => [
                        'title' => 'Test Video',
                        'publishedAt' => now()->toIso8601String(),
                        'thumbnails' => [
                            'high' => ['url' => 'https://i.ytimg.com/vi/' . $expectedId . '/hqdefault.jpg'],
                        ],
                    ],
                ]],
            ], 200),
            '*' => 200,
        ],
    );

    ReactYouTubeVideo::dispatchSync($url);

    Http::assertSent(function ($request) use ($expectedId) {
        return $request->url() === 'https://www.googleapis.com/youtube/v3/videos?id=' . $expectedId . '&key=' . config('services.youtube.key') . '&part=snippet' &&
               $request->method() === 'GET';
    });
})->with([
    // Standard watch URL
    ['https://www.youtube.com/watch?v=dQw4w9WgXcQ', 'dQw4w9WgXcQ'],
    // Short URL
    ['https://youtu.be/dQw4w9WgXcQ', 'dQw4w9WgXcQ'],
    // Embed URL
    ['https://www.youtube.com/embed/dQw4w9WgXcQ', 'dQw4w9WgXcQ'],
    // /v/ URL
    ['https://www.youtube.com/v/dQw4w9WgXcQ', 'dQw4w9WgXcQ'],
    // Shorts URL
    ['https://www.youtube.com/shorts/dQw4w9WgXcQ', 'dQw4w9WgXcQ'],
    // With extra params
    ['https://www.youtube.com/watch?v=dQw4w9WgXcQ&t=42s', 'dQw4w9WgXcQ'],
    // With www missing
    ['https://youtube.com/watch?v=dQw4w9WgXcQ', 'dQw4w9WgXcQ'],
]);

it('does nothing for invalid YouTube URLs', function () {
    Http::fake();

    ReactYouTubeVideo::dispatchSync('https://example.com/not-a-youtube-url');

    Http::assertNothingSent();
});

it('sends an event to Soubiran with video details', function () {
    $url = 'https://www.youtube.com/watch?v=dQw4w9WgXcQ';
    $payload = [
        'title' => 'Never Gonna Give You Up',
        'date' => now()->toIso8601String(),
        'thumbnail' => 'https://i.ytimg.com/vi/dQw4w9WgXcQ/hqdefault.jpg',
    ];

    $signature = 'sha256=' . hash_hmac('sha256', json_encode([ 'url' => $url, ...$payload]), config('services.soubiran.webhook_secret'));

    Http::fake([
        'https://www.googleapis.com/youtube/v3/videos*' => Http::response([
            'items' => [[
                'snippet' => [
                    'title' => $payload['title'],
                    'publishedAt' => $payload['date'],
                    'thumbnails' => [
                        'high' => ['url' => $payload['thumbnail']],
                    ],
                ],
            ]],
        ], 200),
        '*' => 200
    ]);

    ReactYouTubeVideo::dispatchSync($url);

    Http::assertSent(function ($request) use ($url, $signature, $payload) {
        return $request->url() === config('services.soubiran.webhook_url')
            && $request->header('X-Soubiran-Signature-256')[0] === $signature
            && $request->header('X-Soubiran-Event')[0] === 'youtube.video'
            && $request->header('Content-Type')[0] === 'application/json'
            && $request['url'] === $url
            && $request['title'] === $payload['title']
            && $request['date'] === $payload['date']
            && $request['thumbnail'] === $payload['thumbnail'];
    });
})->only();
