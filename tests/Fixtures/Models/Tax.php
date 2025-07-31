<?php

namespace LakM\CommentsAdminPanel\Tests\Fixtures\Models;

use Illuminate\Database\Eloquent\Model;
use LakM\Commenter\Concerns\Commentable;
use LakM\Commenter\Contracts\CommentableContract;

class Tax extends Model implements CommentableContract
{
    use Commentable;
}
