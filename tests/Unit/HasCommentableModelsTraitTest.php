<?php

use LakM\CommentsAdminPanel\Livewire\Admin\Concerns\HasCommentableModels;
use LakM\CommentsAdminPanel\Tests\Fixtures\Models\Blog\Post;
use LakM\CommentsAdminPanel\Tests\Fixtures\Models\Blog\Report\Report;
use LakM\CommentsAdminPanel\Tests\Fixtures\Models\Blog\Tag;
use LakM\CommentsAdminPanel\Tests\Fixtures\Models\Tax;

beforeEach(function () {
    $ds = DIRECTORY_SEPARATOR;
    $this->ds = DIRECTORY_SEPARATOR;

    $this->ob = new class () {
        use HasCommentableModels;
    };

    $this->app->useAppPath(__DIR__ . $ds . '../Fixtures');

    $this->modelsPath = app_path('Models');

    $this->modelsNamespace = "LakM{$ds}CommentsAdminPanel{$ds}Tests{$ds}Fixtures{$ds}Models";
});

it('can discover all the commentable models', function () {
    $modelsRootNamespace = "LakM{$this->ds}CommentsAdminPanel{$this->ds}Tests{$this->ds}Fixtures";

    $models = $this->ob->getModels($this->modelsPath, $this->modelsNamespace, $modelsRootNamespace);

    $models = collect($models)
        ->transform(function (array $model) {
            return get_class($model['instance']);
        })->flatten();

    expect($models->all())->toMatchArray(
        [
            Post::class,
            Report::class,
            Tag::class,
            Tax::class,
        ]
    );
});

it('discover only models in commentable_models array when it is not empty', function () {
    config(['comments-admin-panel.commentable_models' => [Post::class]]);

    $ob = new class () {
        use HasCommentableModels;
    };

    $models = $ob->getModels($this->modelsPath, $this->modelsNamespace);

    $models = collect($models)
        ->transform(function (array $model) {
            return get_class($model['instance']);
        })->flatten();

    expect($models->all())->toMatchArray(
        [
            Post::class
        ]
    );
});
