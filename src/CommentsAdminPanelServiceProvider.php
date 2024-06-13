<?php

namespace LakM\Comments\AdminPanel;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use LakM\Comments\AdminPanel\Livewire\Admin\CommentForm;
use LakM\Comments\AdminPanel\Livewire\Admin\CommentList;
use LakM\Comments\AdminPanel\Livewire\Admin\Dashboard;
use LakM\Comments\AdminPanel\Livewire\Admin\ModelList;
use LakM\Comments\AdminPanel\Livewire\Admin\ReplyForm;
use LakM\Comments\AdminPanel\Livewire\Admin\ReplyList;
use LakM\Comments\AdminPanel\Livewire\Admin\Sidebar;
use LakM\Comments\AdminPanel\Console\InstallCommand;
use Livewire\Livewire;


class CommentsAdminPanelServiceProvider extends ServiceProvider
{
    public const MANIFEST_PATH = __DIR__ . '/../public/build/manifest.json';

    public function boot(): void
    {
        $this->loadRoutes();
        $this->setViews();
        $this->setComponents();
        $this->setBladeDirectives();
        $this->registerCommands();
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/comments-admin-panel.php', 'comments-admin-panel');

        $this->configPublishing();
    }

    protected function loadRoutes(): void
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
    }

    protected function setViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'comments-admin-panel');
    }

    protected function setComponents(): void
    {
        Blade::componentNamespace('LakM\\Comments\\AdminPanel\\Views\\Components', 'comments-admin-panel');

        Livewire::component('comments-admin-sidebar', Sidebar::class);
        Livewire::component('comments-model-list', ModelList::class);
        Livewire::component('comments-admin-list', CommentList::class);
        Livewire::component('comments-admin-reply-list', ReplyList::class);

        Livewire::component('comments-dashboard', Dashboard::class);
        Livewire::component('admin-comment-form', CommentForm::class);
        Livewire::component('comments-admin-replies-edit-form', ReplyForm::class);
    }

    protected function setBladeDirectives(): void
    {
        Blade::directive('commentsAdminPanelStyles', function () {
            $url = $this->getStyleUrl();
            return "<link rel='stylesheet' href='{$url}'>";
        });
    }

    protected function registerCommands(): void
    {
        if($this->app->runningInConsole()) {
            $this->commands([
                InstallCommand::class,
            ]);
        }
    }

    public function configPublishing(): void
    {
        $this->publishes([
            __DIR__ . '/../config/comments-admin-panel.php' => config_path('comments.php')
        ], 'comments-admin-panel-config');

        $this->publishes([
            __DIR__ . '/../public/vendor/lakm/comments-admin-panel' => public_path('vendor/lakm/comments-admin-panel')
        ], 'comments-admin-panel-assets');

        $this->publishes([
            __DIR__ . '/../resources' => public_path('vendor/comments-admin-panel')
        ], 'comments-admin-panel-resources');
    }

    protected function getStyleUrl(): string
    {
        $stylePath = $this->getManifestData()['resources/js/app.js']['css'][0];

        return asset("vendor/lakm/comments-admin-panel/build/{$stylePath}");
    }

    protected function getManifestData(): array
    {
        return json_decode(file_get_contents(self::MANIFEST_PATH), true);
    }
}
