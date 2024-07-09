<?php

namespace LakM\CommentsAdminPanel\Livewire\Admin;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use LakM\CommentsAdminPanel\Livewire\Admin\Concerns\HasCommentableModels;
use Livewire\Component;

class Sidebar extends Component
{
    use HasCommentableModels;

    public array $models = [];

    public function mount(string $modelPath = null): void
    {
        if (!isset($modelPath)) {
            $modelPath = app_path('Models');
        }

        $this->models = $this->getModels($modelPath);
    }

    public function go($route): void
    {
        $this->redirect($route);
    }

    public function navigate(string $key): void
    {
        $this->redirect($this->getModelRoute($key));
    }

    public function getModelRoute(string $key): string
    {
        return route('admin.models.comments.index', ['name' => str($key)->replace('\\', '')->kebab()->plural()]);
    }

    public function render(): View|Factory|Application
    {
        return view('comments-admin-panel::livewire.admin.sidebar');
    }
}
