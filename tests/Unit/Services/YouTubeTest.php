<?php

declare(strict_types=1);

use App\Services\YouTube;
use Illuminate\Support\Facades\Http;

it('returns video details for a valid video ID', function () {
    Http::fake([
        'https://www.googleapis.com/youtube/v3/videos*' => Http::response([
            'items' => [[
                'snippet' => [
                    'title' => 'Test Video',
                    'publishedAt' => '2024-01-01T00:00:00Z',
                    'thumbnails' => [
                        'high' => ['url' => 'https://img.youtube.com/vi/test/high.jpg'],
                    ],
                ],
            ]],
        ], 200),
    ]);

    $service = new YouTube();
    $details = $service->getVideoDetails('test');

    expect($details)->toBe([
        'title' => 'Test Video',
        'date' => '2024-01-01T00:00:00Z',
        'thumbnail' => 'https://img.youtube.com/vi/test/high.jpg',
    ]);
});

it('returns null for an invalid video ID', function () {
    Http::fake([
        'https://www.googleapis.com/youtube/v3/videos*' => Http::response([
            'items' => [],
        ], 200),
    ]);

    $service = new YouTube();
    $details = $service->getVideoDetails('invalid');

    expect($details)->toBeNull();
});
