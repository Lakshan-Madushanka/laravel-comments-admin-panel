<div>
    @php
        $mName = str($modelName)->replace('\\', '')->kebab()->plural();
    @endphp
    <x-slot:linearNavigation>
        <x-filament::breadcrumbs :breadcrumbs="[
                route('admin.comments.dashboard') => 'Dashboard',
                route('admin.models.comments.index', ['name' => $mName]) => \str($modelName)->plural()->kebab()->title(),
                 route('admin.models.comments.index', ['name' => $mName]) . '#' => $modelId,
                route('admin.models.comments.show', ['modelName' => $mName, 'id' => $modelId]) => 'Comments',
                route('admin.comments.edit', ['modelName' => $mName, 'modelId' => $modelId, 'comment' => $comment]) . '#' => $comment->getKey(),
                route('admin.comments.replies.index',  ['modelName' => $mName, 'modelId' => $modelId, 'comment' => $comment]) => 'Replies',
                url()->current() . '#' => $reply->getKey(),
                url()->current() => 'Edit'
            ]"
        />
    </x-slot:linearNavigation>

    <x-slot:header>
        <h1>Edit Reply Id &rarr; {{$reply->getKey()}}</h1>
    </x-slot:header>

    <form wire:submit="save">
        {{ $this->form }}

        <div class="my-4">
            <x-commenter::button type="submit">
                Submit
            </x-commenter::button>
        </div>
    </form>

    <x-filament-actions::modals/>

</div>
