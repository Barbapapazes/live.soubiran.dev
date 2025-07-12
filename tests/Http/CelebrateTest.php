<?php

declare(strict_types=1);

use App\Events\CelebrationOver;
use App\Models\User;
use Illuminate\Support\Facades\Event;

beforeEach(function () {
    Event::fake();

    $this->user = User::factory()->hasAccess()->create();
});

it('requires authentication', function () {
    $this
        ->get(route('dashboard'))
        ->assertRedirect('/login');
});

it('requires access', function () {
    $this
        ->actingAs(User::factory()->create())
        ->get(route('dashboard'))
        ->assertForbidden();
});

it('celebrates', function () {
    $this
        ->actingAs($this->user)
        ->post(route('celebrate'))
        ->assertOk();

    Event::assertDispatched(CelebrationOver::class);
});
