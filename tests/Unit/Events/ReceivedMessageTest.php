<?php

declare(strict_types=1);

use App\Events\ReceivedMessage;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;

beforeEach(function () {
    $this->message = 'Hello, world!';
    $this->username = 'testuser';
    $this->color = '#000000';
});

it('should broadcast message, username and color', function () {
    $event = new ReceivedMessage(
        $this->message,
        $this->username,
        $this->color
    );

    expect($event->message)->toBe($this->message);
    expect($event->username)->toBe($this->username);
    expect($event->color)->toBe($this->color);
});

it('should broadcast now', function () {
    $event = new ReceivedMessage(
        $this->message,
        $this->username,
        $this->color
    );

    expect($event)->toBeInstanceOf(ShouldBroadcastNow::class);
});

it('can broadcast received message event', function () {
    $event = new ReceivedMessage(
        $this->message,
        $this->username,
        $this->color
    );

    expect($event->broadcastOn()->name)->toBe('private-chat');
});
