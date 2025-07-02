<?php

declare(strict_types=1);

use App\Events\ShowLiveQuestion;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

beforeEach(function () {
    $this->question = 'What is the meaning of life?';
    $this->username = 'testuser';
    $this->color = '#000000';
});

it('should broadcast question, username and color', function () {
    $event = new ShowLiveQuestion(
        $this->question,
        $this->username,
        $this->color
    );

    expect($event->question)->toBe($this->question);
    expect($event->username)->toBe($this->username);
    expect($event->color)->toBe($this->color);
});

it('should broadcast now', function () {
    $event = new ShowLiveQuestion(
        $this->question,
        $this->username,
        $this->color
    );

    expect($event)->toBeInstanceOf(ShouldBroadcastNow::class);
});

it('can broadcast show live question event', function () {
    $event = new ShowLiveQuestion(
        $this->question,
        $this->username,
        $this->color
    );

    expect($event->broadcastOn()->name)->toBe('live');
});
