<?php

namespace LakM\CommentsAdminPanel\Livewire\Admin;

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
use LakM\Comments\Models\Reply;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ReplyForm extends Component implements HasForms
{
    use InteractsWithForms;

    public Reply $reply;
    public Comment $comment;

    public string $modelName;
    public mixed $modelId;

    public ?array $data = [];

    public function mount(Reply $reply, string $modelName, mixed $modelId, Comment $comment): void
    {
        $this->form->fill($reply->toArray());

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
        if ($this->reply->fill($this->form->getState())->save()) {
            Notification::make()
                ->title('Reply edited successfully')
                ->success()
                ->send();
        }
    }

    #[Layout('comments-admin-panel::layouts.admin')]
    public function render(): View|Factory|Application
    {
        return view('comments-admin-panel::livewire.admin.reply-form');
    }
}
