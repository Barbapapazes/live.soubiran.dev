<?php

declare(strict_types=1);

it('validates a Twitch webhook request', function () {
    $this->postJson(route('webhook.twitch'))
        ->assertForbidden();
});
