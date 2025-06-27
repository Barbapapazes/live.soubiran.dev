<?php

namespace App\Jobs;

use App\Services\Soubiran;
use App\Services\YouTube;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ReactYouTubeVideo implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $youtubeUrl = ''
    )
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(YouTube $youtube, Soubiran $soubiran): void
    {
        if (empty($this->videoId())) {
            return;
        }

        $details = $youtube->getVideoDetails($this->videoId());

        $soubiran->sendEvent('youtube.video', [
            'url' => $this->youtubeUrl,
            'title' => $details['title'],
            'date' => $details['date'],
            'thumbnail' => $details['thumbnail'],
        ]);
    }

    /**
     * Extract the video ID from the YouTube URL.
     */
    protected function videoId(): string
    {
        preg_match('/https:\/\/(?:www\.)?youtu(?:\.be\/|be\.com\/(?:watch\?v=|embed\/|v\/|shorts\/))([^&\n]{11})/', $this->youtubeUrl, $matches);
        return $matches[1] ?? '';
    }
}
