<?php

namespace LakM\CommentsAdminPanel\Livewire\Admin;

use Filament\Forms\ComponentContainer;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use GrahamCampbell\Security\Facades\Security;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use LakM\Comments\Models\Comment;
use Livewire\Attributes\Layout;
use Livewire\Component;

/**
 * @property ComponentContainer $form
 */
class CommentForm extends Component implements HasForms
{
    use InteractsWithForms;

    public Comment $comment;

    public ?array $data = [];

    public string $modelName;

    public mixed $modelId;

    public function mount(Comment $comment, string $modelName, mixed $modelId): void
    {
        $this->form->fill($comment->toArray());

        $this->modelName = str($modelName)->singular()->studly();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Toggle::make('approved')
                    ->required(),
                RichEditor::make('text')
                    ->disableToolbarButtons(['attachFiles'])
                    ->formatStateUsing(fn (string $state) => Security::clean($state))
                    ->label('Comment')
                    ->required(),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        if ($this->comment->fill($this->form->getState())->save()) {
            Notification::make()
                ->title('Comment edited successfully')
                ->success()
                ->send();
        }
    }

    #[Layout('comments-admin-panel::layouts.admin')]
    public function render(): View|Factory|Application
    {
        return view('comments-admin-panel::livewire.admin.comment-form');
    }
}
