<?php

declare(strict_types=1);

use App\Events\ShowLiveQuestion;
use App\Models\User;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    $this->user = User::factory()->create();

    $this->payload = [
        'question' => 'What is the meaning of life?',
        'username' => 'testuser',
        'color' => '#000000',
    ];
});

it('requires authentication', function () {
    $this->post(route('live-question.show'))
        ->assertRedirect('/login');
});

it('triggers the event to show the question', function () {
    Event::fake();

    $this->actingAs($this->user)
        ->post(route('live-question.show'), $this->payload)
        ->assertOk();

    Event::assertDispatched(ShowLiveQuestion::class, function ($event) {
        return $event->question === $this->payload['question'] &&
                $event->username === $this->payload['username'] &&
               $event->color === $this->payload['color'];
    });
});
