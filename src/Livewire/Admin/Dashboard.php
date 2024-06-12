<?php

namespace LakM\Comments\AdminPanel\Livewire\Admin;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use LakM\Comments\Actions\DeleteCommentAction;
use LakM\Comments\AdminPanel\Livewire\Admin\Concerns\HasCommentableModels;
use LakM\Comments\Models\Comment;
use LakM\Comments\Repository;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

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
        return (new $namespace)->guestModeEnabled();
    }

    #[Layout('comments-admin-panel::layouts.admin')]
    public function render(): View|Factory|Application
    {
        return view('comments-admin-panel::livewire.admin.dashboard');
    }
}
