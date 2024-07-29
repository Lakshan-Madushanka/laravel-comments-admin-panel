<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

use LakM\CommentsAdminPanel\Tests\Fixtures\Models\User;
use LakM\CommentsAdminPanel\Tests\TestCase;

use function Pest\Laravel\actingAs;

uses(TestCase::class)->in('Feature', 'Unit');

function actAsAuth(): User
{
    $user = user();

    actingAs($user, 'web');

    return $user;
}

function user(): User
{
    return User::create(['name' => fake()->name(), 'email' => fake()->email()]);
}
