<?php

namespace LakM\CommentsAdminPanel\Livewire\Admin;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use LakM\Comments\Models\Comment;
use LakM\Comments\Models\Reply;
use LakM\CommentsAdminPanel\Repository;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ReplyList extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public Comment $comment;
    public string $modelName;
    public mixed $modelId;

    public function mount(Comment $comment, string $modelName, mixed $modelId): void
    {
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn () => $this->query())
            ->columns([
                TextColumn::make('id')
                    ->label('Reply Id'),
                TextColumn::make('text')
                    ->searchable()
                    ->limit(100)
                    ->html(),
                ToggleColumn::make('approved')
                    ->label('is_approved'),
                TextColumn::make('score')
                    ->getStateUsing(fn (Model $record) => $this->calcScore($record))
                    ->sortable(query: function (Builder $query, string $direction) {
                        return $query->orderBy(
                            DB::raw('reactions_count - (dislikes_count * 2)'),
                            $direction
                        );
                    }),
                ...$this->addReactionColumns(),
                TextColumn::make('created_at')->dateTime()->sortable(),
                TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->defaultSort('score', 'desc')
            ->filters([
                TernaryFilter::make('Approved'),
            ])
            ->actions([
                Action::make('Edit')
                    ->url(fn (Model $record) => route('admin.comments.replies.edit', [
                        'reply' => $record->getKey(),
                        'comment' => $this->comment->getKey(),
                        'modelName' => $this->modelName,
                        'modelId' => $this->modelId,
                    ]))
                    ->icon('heroicon-m-pencil-square'),
                Action::make('delete')
                    ->icon('heroicon-m-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (Reply $record) => $record->delete()),
            ])
            ->bulkActions([
                // ...
            ]);
    }

    private function calcScore(Model $record): int
    {
        return $record->reactions_count - ($record->dislikes_count * 2);
    }

    private function query(): HasMany
    {
        return Repository::repliesOf($this->comment);
    }

    private function addReactionColumns(): array
    {
        $reactions = [];

        foreach (config('comments.reactions') as $key => $value) {
            $reactions[] = TextColumn::make(Str::plural($key) . '_count')->sortable();
        }

        return $reactions;
    }

    private function setModel(string $modelName, mixed $id): void
    {
        $modelName = 'App\\Models\\' . str($modelName)->singular()->studly();

        $this->model = $modelName::find($id);
    }

    #[Layout('comments-admin-panel::layouts.admin')]
    public function render(): View|Factory|Application
    {
        return view('comments-admin-panel::livewire.admin.reply-list');
    }
}
