<div>
    <x-slot:linearNavigation>
        <x-filament::breadcrumbs :breadcrumbs="[
                route('admin.comments.dashboard') => 'Dashboard',
                route('admin.models.comments.index', ['name' => str($modelName)->replace('\\', '')->kebab()->plural()]) =>
                    str($modelName)->plural()->snake('-')->kebab()->title(),
                route('admin.models.comments.index', ['name' => str($modelName)->replace('\\', '')->kebab()->plural()]) . '#' =>
                    $modelId,
                route('admin.models.comments.show', ['modelName' => $modelName, 'id' => $modelId]) => 'Comments',
                route('admin.models.comments.show', ['modelName' => $modelName, 'id' => $modelId]) . '#' => $comment->getKey(),
                url()->current() . '#' => 'Replies',
            ]"
        />
    </x-slot:linearNavigation>

    <x-slot:header>
        <h1>Replies of Comment Id &rarr; {{$comment->getKey()}}</h1>
    </x-slot:header>
    <div>
        {{$this->table}}
    </div>
</div>
