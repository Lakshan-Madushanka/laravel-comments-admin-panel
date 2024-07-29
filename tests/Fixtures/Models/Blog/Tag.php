<?php

namespace LakM\CommentsAdminPanel\Tests\Fixtures\Models\Blog;

use Illuminate\Database\Eloquent\Model;
use LakM\Comments\Concerns\Commentable;
use LakM\Comments\Contracts\CommentableContract;

class Tag extends Model implements CommentableContract
{
    use Commentable;

    protected $guarded = [];
}
