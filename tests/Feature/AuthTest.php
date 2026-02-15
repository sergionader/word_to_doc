<?php

use App\Models\User;
use Livewire\Volt\Volt;

it('can render registration page', function () {
    $this->get('/register')->assertStatus(200);
});

it('can register a new user', function () {
    $component = Volt::test('pages.auth.register')
        ->set('name', 'Test User')
        ->set('email', 'test@example.com')
        ->set('password', 'password123')
        ->set('password_confirmation', 'password123');

    $component->call('register');

    $component->assertHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});

it('can render login page', function () {
    $this->get('/login')->assertStatus(200);
});

it('can login', function () {
    $user = User::factory()->create();

    $component = Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'password');

    $component->call('login');

    $component->assertHasNoErrors();
    $this->assertAuthenticated();
});

it('cannot login with wrong password', function () {
    $user = User::factory()->create();

    $component = Volt::test('pages.auth.login')
        ->set('form.email', $user->email)
        ->set('form.password', 'wrong-password');

    $component->call('login');

    $component->assertHasErrors();
    $this->assertGuest();
});

it('redirects unauthenticated users to login', function () {
    $this->get('/browse')->assertRedirect('/login');
    $this->get('/convert')->assertRedirect('/login');
    $this->get('/history')->assertRedirect('/login');
});

it('can logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $component = Volt::test('layout.navigation');
    $component->call('logout');

    $component->assertRedirect('/');
    $this->assertGuest();
});
