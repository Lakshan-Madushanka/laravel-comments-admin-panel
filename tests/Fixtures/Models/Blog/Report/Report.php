<?php

namespace LakM\CommentsAdminPanel\Tests\Fixtures\Models\Blog\Report;

use Illuminate\Database\Eloquent\Model;
use LakM\Comments\Concerns\Commentable;
use LakM\Comments\Contracts\CommentableContract;

class Report extends Model implements CommentableContract
{
    use Commentable;

    protected $guarded = [];
}
