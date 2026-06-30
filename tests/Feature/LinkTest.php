<?php

use App\Models\Link;
use App\Models\User;

test('a link code is generated automatically', function () {
    $user = User::factory()->create();
    $link = Link::create([
        'user_id' => $user->id,
        'original_url' => 'https://yandex.ru',
    ]);

    expect($link->code)
        ->not->toBeNull()
        ->and(strlen($link->code))->toBeGreaterThanOrEqual(Link::CODE_MIN_LENGTH);
});

test('a link redirects to the original url', function () {
    $user = User::factory()->create();
    Link::create([
        'user_id' => $user->id,
        'original_url' => 'https://google.com',
        'code' => 'asd123',
    ]);

    $response = $this->get('/asd123');

    $response->assertRedirect('https://google.com');
});

test('a link visit is recorded in statistics', function () {
    $user = User::factory()->create();
    $link = Link::create([
        'user_id' => $user->id,
        'original_url' => 'https://example.com',
        'code' => 'zxc543',
    ]);

    $this->get('/zxc543');

    $this->assertDatabaseHas('link_visits', [
        'link_id' => $link->id,
        'ip_address' => '127.0.0.1',
    ]);

    expect($link->visits()->count())->toBe(1);
});

test('redirecting to a nonexistent code returns 404', function () {
    $response = $this->get('/nonexistent');
    $response->assertStatus(404);
});

test('unauthenticated users cannot see links in filament', function () {
    $response = $this->get('/links');
    $response->assertRedirect('/login');
});
