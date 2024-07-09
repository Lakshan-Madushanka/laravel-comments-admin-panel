<?php

use Illuminate\Support\Facades\Route;
use LakM\CommentsAdminPanel\Livewire\Admin\CommentForm;
use LakM\CommentsAdminPanel\Livewire\Admin\CommentList;
use LakM\CommentsAdminPanel\Livewire\Admin\Dashboard;
use LakM\CommentsAdminPanel\Livewire\Admin\ModelList;
use LakM\CommentsAdminPanel\Livewire\Admin\ReplyForm;
use LakM\CommentsAdminPanel\Livewire\Admin\ReplyList;

$prefix = config('comments-admin-panel.routes.prefix');

Route::middleware([...config('comments-admin-panel.routes.middlewares')])
    ->prefix($prefix)
    ->name('admin.')
    ->group(function () {
        Route::get('comments/dashboard', Dashboard::class)
            ->name('comments.dashboard');

        Route::get('{name}/comments', ModelList::class)
            ->name('models.comments.index');

        Route::get('{modelName}/{id}/comments', CommentList::class)
            ->name('models.comments.show');

        Route::get('{modelName}/{modelId}/comments/{comment}/edit', CommentForm::class)
            ->name('comments.edit');

        Route::get('{modelName}/{modelId}/comments/{comment}/replies', ReplyList::class)
            ->name('comments.replies.index');

        Route::get('{modelName}/{modelId}/comments/{comment}/replies/{reply}/edit', ReplyForm::class)
            ->name('comments.replies.edit')
            ->scopeBindings();
    });
