<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('admin can access dashboard', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get('/dashboard')
        ->assertSuccessful();
});

test('pegawai can access dashboard', function () {
    $pegawai = User::factory()->pegawai()->create();

    $this->actingAs($pegawai)
        ->get('/dashboard')
        ->assertSuccessful();
});

test('user can access dashboard', function () {
    $user = User::factory()->users()->create();

    $this->actingAs($user)
        ->get('/dashboard')
        ->assertSuccessful();
});

test('admin can access user management', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get(route('user.index'))
        ->assertSuccessful();
});

test('pegawai cannot access user management', function () {
    $pegawai = User::factory()->pegawai()->create();

    $this->actingAs($pegawai)
        ->get(route('user.index'))
        ->assertForbidden();
});

test('user cannot access user management', function () {
    $user = User::factory()->users()->create();

    $this->actingAs($user)
        ->get(route('user.index'))
        ->assertForbidden();
});

test('unauthenticated user is redirected to login', function () {
    $this->get('/dashboard')
        ->assertRedirect('/login');
});
