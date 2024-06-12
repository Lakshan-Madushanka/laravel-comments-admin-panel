<div>
    <x-slot:linearNavigation>
        <x-filament::breadcrumbs :breadcrumbs="[
                route('admin.comments.dashboard') => 'Dashboard',
                route('admin.models.comments.index', ['name' => str($modelName)->replace('\\', '')->kebab()->plural()]) =>
                    str($modelName)->plural()->snake('-')->kebab()->title(),
                url()->current() => $model->getKey(),
                url()->current() . '#' => 'Replies',
            ]"
        />
    </x-slot:linearNavigation>

    <x-slot:header>
        <h1>Comments of Post Id &rarr; {{$model->getKey()}}</h1>
    </x-slot:header>

    <div>
        {{$this->table}}
    </div>
</div>
