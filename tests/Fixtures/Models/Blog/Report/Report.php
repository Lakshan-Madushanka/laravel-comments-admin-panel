<?php

namespace LakM\CommentsAdminPanel\Tests\Fixtures\Models\Blog\Report;

use Illuminate\Database\Eloquent\Model;
use LakM\Commenter\Concerns\Commentable;
use LakM\Commenter\Contracts\CommentableContract;

class Report extends Model implements CommentableContract
{
    use Commentable;

    protected $guarded = [];
}
