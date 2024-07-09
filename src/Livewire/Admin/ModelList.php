<?php

namespace LakM\CommentsAdminPanel\Livewire\Admin;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use LakM\CommentsAdminPanel\Repository;
use Livewire\Attributes\Layout;
use Livewire\Component;

class ModelList extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public string $name;

    public string $modelName;

    public Model $model;

    public bool $isGuestMode;

    public function mount(string $name): void
    {
        $this->name = $name;

        $this->setModel($name);

        $this->setModelName($name);

        $this->isGuestMode = $this->model->guestModeEnabled();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->query())
            ->columns([
                TextColumn::make('commentable_id')
                    ->label($this->modelName . ' Id')
                    ->searchable(query: function (Builder $query, string $search) {
                        return $query->where('commentable_id', $search);
                    }, isIndividual: true),

                TextColumn::make('comments_count')
                    ->sortable(),

            ])
            ->filters([
            ])
            ->actions([
                Action::make('comments')
                    ->url(fn (Model $record) => route('admin.models.comments.show', [
                        'modelName' => $this->name,
                        'id' => $record->commentable_id,
                    ]))
                    ->icon('heroicon-m-chat-bubble-bottom-center-text'),
            ])
            ->bulkActions([
                // ...
            ]);
    }

    private function setModel(string $name): void
    {
        $modelName = collect(explode('-', $name))
            ->map(function (string $val) {
                return str($val)->studly();
            });

        $modelName = 'App\\Models\\' . Str::singular($modelName->implode('\\'));
        $this->model = new $modelName();
    }

    private function setModelName(string $name): void
    {
        $this->modelName = str($name)->afterLast('-')->studly()->singular();
    }

    private function query(): Builder
    {
        return Repository::commentsOfModelType($this->model);
    }


    #[Layout('comments-admin-panel::layouts.admin')]
    public function render(): View|Factory|Application
    {
        return view('comments-admin-panel::livewire.admin.model-list');
    }
}
