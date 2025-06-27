<?php

use App\Events\ConfettiExplode;
use App\Events\ConfettiLocked;
use App\Jobs\ReactYouTubeVideo;
use App\Services\TwitchEvents;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;

beforeEach(function () {
    $this->twitchEvents = app()->make(TwitchEvents::class);
});

it('triggers a confetti event', function () {
    Event::fake();

    $this->twitchEvents->confetti();

    Event::assertDispatched(ConfettiExplode::class);
});

it('locks the confetti event for 10 seconds once it is triggered', function () {
    Event::fake();

    $this->twitchEvents->confetti();
    $this->twitchEvents->confetti();

    Event::assertDispatched(ConfettiExplode::class, 1);

    Carbon::setTestNow(now()->addSeconds(10));

    $this->twitchEvents->confetti();

    Event::assertDispatched(ConfettiExplode::class, 2);
});

it('sends a locked confetti event when the confetti command is ignored due to an existing lock', function () {
    Event::fake();

    $this->twitchEvents->confetti();
    Event::assertDispatched(ConfettiExplode::class, 1);

    // Simulate a second request while the lock is still active
    $this->twitchEvents->confetti();
    Event::assertDispatched(ConfettiLocked::class);
});

it('triggers a react event with a URL', function (string $url) {
    Queue::fake();

    $this->twitchEvents->react($url);

    Queue::assertPushed(ReactYouTubeVideo::class, function ($event) use ($url) {
        return $event->url === $url;
    });
})->with([
    'https://youtube.com/watch?v=abcdefghijk',
    'https://www.youtube.com/watch?v=abcdefghijk',
    'https://youtu.be/abcdefghijk',
    'https://www.youtu.be/abcdefghijk',
    'https://youtube.com/watch?v=abcdefghijk',
]);

it('ignores a react event with an empty URL', function () {
    Queue::fake();

    $this->twitchEvents->react('');

    Queue::assertNotPushed(ReactYouTubeVideo::class);
});

it('ignores a react event when the URL is not from YouTube', function (string $url) {
    Queue::fake();

    $this->twitchEvents->react($url);

    Queue::assertNotPushed(ReactYouTubeVideo::class);
})
->with([
    'https://example.com',
    'https://www.example.com',
    'https://example.com/watch?v=abcdefghijk',
    'https://youtube.com',
    'https://youtu.be',
]);
