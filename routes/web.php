<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use LakM\Comments\AdminPanel\Livewire\Admin\CommentForm;
use LakM\Comments\AdminPanel\Livewire\Admin\CommentList;
use LakM\Comments\AdminPanel\Livewire\Admin\Dashboard;
use LakM\Comments\AdminPanel\Livewire\Admin\ModelList;
use LakM\Comments\AdminPanel\Livewire\Admin\ReplyForm;
use LakM\Comments\AdminPanel\Livewire\Admin\ReplyList;
use LakM\Comments\Models\Reply;

$user1 = User::query()->createOrFirst(['email' => 'lak'],
    ['name' => 'lak2', 'password' => 'password', 'email' => 'lak']);

if (!Auth::check()) {
    Auth::login($user1);
}



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
