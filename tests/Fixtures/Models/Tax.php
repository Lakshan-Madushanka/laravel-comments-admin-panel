<?php

namespace LakM\CommentsAdminPanel\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use LakM\Comments\Concerns\Commentable;
use LakM\Comments\Contracts\CommentableContract;

class Tax extends Model implements CommentableContract
{
    use Commentable;
}
