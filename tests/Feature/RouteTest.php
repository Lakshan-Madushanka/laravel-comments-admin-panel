<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Route;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

beforeEach(function () {
    actAsAuth();
});

it('has routes', function () {
    expect(Route::has('admin.comments.dashboard'))->toBeTrue();
});

it('can throw 404 status when disable routes', function () {
    config(['comments-admin-panel.enabled' => false]);

    get(\route('admin.comments.dashboard'))
        ->assertStatus(404);

})->throws(AuthorizationException::class);

it('can throw 404 status production mode', function () {
    app()['env'] = 'production';

    get(\route('admin.comments.dashboard'))
        ->assertStatus(404);

})->throws(AuthorizationException::class);

it('can allows to access when canAccessAdminPanel method returns true', function () {
    app()['env'] = 'production';

    $user = new class extends User {
        public function canAccessAdminPanel(): bool
        {
            return true;
        }
    };

    actingAs($user);

    get(\route('admin.comments.dashboard'))
        ->assertOk();
});
