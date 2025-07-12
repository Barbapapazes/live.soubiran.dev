<?php

declare(strict_types=1);

use App\Models\User;
use Inertia\Testing\AssertableInertia;

beforeEach(function () {
    $this->user = User::factory()->hasAccess()->create();
});

it('requires authentication', function () {
    $this->get(route('dashboard'))
        ->assertRedirect('/login');
});

it('renders the dashboard page', function () {
    $this->actingAs($this->user)
        ->get(route('dashboard'))
        ->assertInertia(fn (AssertableInertia $page) => $page
            ->component('dashboard')
        );
});
