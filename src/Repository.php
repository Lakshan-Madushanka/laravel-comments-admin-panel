<?php

namespace LakM\Comments\AdminPanel;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use LakM\Comments\Model as M;
use LakM\Comments\Models\Comment;

class Repository
{
    public static function commentsCountOf(Model $model): int
    {
        $alias = $model->getMorphClass();

        return M::commentQuery()->where('commentable_type', $alias)->count();
    }

    public static function commentsOf(Model $model)
    {
        return $model
            ->comments()
            ->withCount([
                'replies',
                'reactions',
                'replyReactions',
                'replyReactions as reply_reactons_dislikes_count' => function (Builder $query) {
                    $query->where('type', 'dislike');
                },
            ])
            ->withCount(self::addCount());
    }

    public static function commentsOfModelType(Model $model): Builder
    {
        $alias = $model->getMorphClass();

        return M::commentQuery()
            ->selectRaw('*, count(1) as comments_count')
            ->where('commentable_type', $alias)
            ->groupBy('commentable_type', 'commentable_id');
    }

    public static function repliesOf(Comment $comment)
    {
        return $comment
            ->replies()
            ->withCount([
                'reactions',
            ])
            ->withCount(self::addCount());
    }

    public static function addCount(): array
    {
        $count = [];

        foreach (array_keys(config('comments.reactions')) as $reaction) {
            $name = Str::plural($reaction);
            $key = "reactions as {$name}_count";
            $count[$key] = function (Builder $query) use ($reaction) {
                return $query->whereType($reaction);
            };
        }
        return $count;
    }

}
