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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use LakM\Comments\Models\Comment;
use LakM\CommentsAdminPanel\Repository;
use Livewire\Attributes\Layout;
use Livewire\Component;

class CommentList extends Component implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    public string $modelName;
    public Model $model;

    public function mount(string $modelName, mixed $id): void
    {
        $this->setModel($modelName, $id);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(fn () => $this->query())
            ->columns([
                TextColumn::make('id')
                    ->label('Comment Id'),
                TextColumn::make('text')
                    ->searchable()
                    ->limit(100)
                    ->toggleable()
                    ->html(),
                ToggleColumn::make('approved')
                    ->toggleable()
                    ->label('is_approved'),
                TextColumn::make('score')
                    ->toggleable()
                    ->getStateUsing(fn (Comment $record) => $this->calcScore($record))
                    ->sortable(query: function (Builder $query, string $direction) {
                        return $query->orderBy(
                            DB::raw('reactions_count - (dislikes_count * 2) + (replies_count * 2) + reply_reactions_count - (reply_reactons_dislikes_count * 2)'),
                            $direction
                        );
                    }),
                TextColumn::make('replies_count')
                    ->toggleable()
                    ->sortable(),
                ...$this->addReactionColumns(),
                TextColumn::make('created_at')
                    ->toggleable()
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->toggleable()
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('score', 'desc')
            ->filters([
                TernaryFilter::make('Approved')
            ])
            ->actions([
                Action::make('Replies')
                    ->url(fn (Model $record) => route('admin.comments.replies.index', [
                        'comment' => $record->getKey(),
                        'modelName' => Str::plural($this->modelName),
                        'modelId' => $this->model->getKey(),
                    ]))
                    ->icon('heroicon-m-chat-bubble-left-ellipsis'),
                Action::make('Edit')
                    ->url(fn (Model $record) => route('admin.comments.edit', [
                        'modelName' => Str::plural($this->modelName),
                        'modelId' => $this->model->getKey(),
                        'comment' => $record->getKey(),
                    ]))
                    ->icon('heroicon-m-pencil-square'),
                Action::make('delete')
                    ->icon('heroicon-m-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (Comment $record) => $record->delete())
            ])
            ->bulkActions([
                // ...
            ]);
    }

    private function calcScore(Comment $record): int
    {
        return $record->reactions_count - ($record->dislikes_count * 2) + ($record->replies_count * 2) + $record->reply_reactions_count - ($record->reply_reactons_dislikes_count * 2);
    }

    private function query()
    {
        return Repository::commentsOf($this->model);
    }

    private function addReactionColumns(): array
    {
        $reactions = [];

        foreach (config('comments.reactions') as $key => $value) {
            $reactions[] = TextColumn::make(Str::plural($key) . '_count')
                ->toggleable()
                ->sortable();
        }

        return $reactions;
    }

    private function setModel(string $name, mixed $id): void
    {
        $modelName = collect(explode('-', $name))
            ->map(function (string $val) {
                return str($val)->studly();
            });

        $modelName = 'App\\Models\\' . Str::singular($modelName->implode('\\'));

        $this->model = $modelName::findOrFail($id);
    }

    #[Layout('comments-admin-panel::layouts.admin')]
    public function render(): View|Factory|Application
    {
        return view('comments-admin-panel::livewire.admin.comment-list');
    }
}
