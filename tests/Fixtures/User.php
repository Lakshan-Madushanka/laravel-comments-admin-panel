<?php

namespace LakM\Comments\AdminPanel\Tests\Fixtures;

use Illuminate\Foundation\Auth\User as Authenticatable;
use LakM\Comments\Concerns\Commenter;

class User extends Authenticatable
{
    protected $guarded = [];
}
