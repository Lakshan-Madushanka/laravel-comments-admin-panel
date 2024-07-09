<?php

namespace LakM\CommentsAdminPanel\Livewire\Admin;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use LakM\CommentsAdminPanel\Livewire\Admin\Concerns\HasCommentableModels;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Dashboard - Comments')]
class Dashboard extends Component
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

    public function isGuestMode(string $namespace)
    {
        return (new $namespace())->guestModeEnabled();
    }

    #[Layout('comments-admin-panel::layouts.admin')]
    public function render(): View|Factory|Application
    {
        return view('comments-admin-panel::livewire.admin.dashboard');
    }
}
