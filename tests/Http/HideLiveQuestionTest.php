<?php

declare(strict_types=1);

use App\Events\HideLiveQuestion;
use App\Models\User;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    $this->user = User::factory()->hasAccess()->create();
});

it('requires authentication', function () {
    $this->delete(route('live-question.hide'))
        ->assertRedirect('/login');
});

it('triggers the event to hide the question', function () {
    Event::fake();

    $this->actingAs($this->user)
        ->delete(route('live-question.hide'))
        ->assertOk();

    Event::assertDispatched(HideLiveQuestion::class);
});
