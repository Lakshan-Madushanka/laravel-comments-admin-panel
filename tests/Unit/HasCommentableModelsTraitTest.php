<?php

use LakM\CommentsAdminPanel\Livewire\Admin\Concerns\HasCommentableModels;

it('can scan all the commentable models', function () {
    $ob = new class () {
        use HasCommentableModels;
    };

    $this->app->useAppPath(__DIR__ . DIRECTORY_SEPARATOR . '../');

    $path = app_path(DIRECTORY_SEPARATOR . 'Fixtures' . DIRECTORY_SEPARATOR . 'Models');


    dd($ob->getModels($path));
})->todo();
