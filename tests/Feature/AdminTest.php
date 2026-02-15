<?php

use App\Models\User;

it('admin can access admin panel', function () {
    $admin = User::factory()->create(['is_admin' => true]);

    $this->actingAs($admin)
        ->get('/admin')
        ->assertStatus(200);
});

it('non-admin cannot access admin panel', function () {
    $user = User::factory()->create(['is_admin' => false]);

    $this->actingAs($user)
        ->get('/admin')
        ->assertStatus(403);
});

it('guest is redirected to admin login', function () {
    $this->get('/admin')
        ->assertRedirect('/admin/login');
});
