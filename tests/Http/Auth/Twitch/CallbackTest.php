<?php

declare(strict_types=1);

use App\Jobs\SubscribeSubscription;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;

it('creates a new user', function () {
    $fakeTwitchUser = fakeTwitchUser();

    $this->get(route('auth.twitch.callback'));

    $this->assertDatabaseHas(User::class, [
        'twitch_id' => $fakeTwitchUser->getId(),
    ]);
});

it('creates a new user if none exists', function () {
    $fakeTwitchUser = fakeTwitchUser();
    User::factory()->create([
        'twitch_id' => $fakeTwitchUser->getId(),
    ]);

    $this->get(route('auth.twitch.callback'));

    $this->assertDatabaseCount(User::class, 1);
    $this->assertDatabaseHas(User::class, [
        'twitch_id' => $fakeTwitchUser->getId(),
    ]);
});

it('blocks access if the user does not have access', function () {
    fakeTwitchUser();

    $this->get(route('auth.twitch.callback'))
        ->assertForbidden();
});

it('allows access if the user has access', function () {
    Queue::fake();

    $fakeTwitchUser = fakeTwitchUser();
    User::factory()->hasAccess()->create([
        'twitch_id' => $fakeTwitchUser->getId(),
    ]);

    $this->get(route('auth.twitch.callback'))
        ->assertOk();
});

it('dispatches a job to subscribe to the channel', function () {
    Queue::fake();

    $fakeTwitchUser = fakeTwitchUser();
    $user = User::factory()->hasAccess()->create([
        'twitch_id' => $fakeTwitchUser->getId(),
    ]);

    $this->get(route('auth.twitch.callback'));

    Queue::assertPushed(function (SubscribeSubscription $job) use ($user) {
        return $job->userId === $user->id
        && $job->broadcasterUserId === $user->twitch_id
        && $job->event === 'channel.chat.message';
    });
});

/**
 * Create a fake Twitch user and mock the Socialite driver.
 *
 * @param  array<string, mixed>  $attributes
 */
function fakeTwitchUser(array $attributes = []): SocialiteUser
{
    $fakeTwitchUser = (new SocialiteUser)->map(attributes: array_merge([
        'id' => '12345',
        'nickname' => 'testuser',
        'email' => 'test@example.com',
        'name' => 'Test User',
        'avatar' => 'https://example.com/avatar.jpg',
    ], $attributes));

    Socialite::shouldReceive('driver->user')->once()->andReturn($fakeTwitchUser);

    return $fakeTwitchUser;
}
