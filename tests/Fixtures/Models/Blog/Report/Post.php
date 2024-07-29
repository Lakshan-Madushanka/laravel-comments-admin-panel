<?php

namespace LakM\CommentsAdminPanel\Tests\Fixtures\Models\Blog\Report;

use Illuminate\Foundation\Auth\User as Authenticatable;
use LakM\Comments\Concerns\Commentable;
use LakM\Comments\Contracts\CommentableContract;

class Post extends Authenticatable implements CommentableContract
{
    use Commentable;

    protected $guarded = [];
}
