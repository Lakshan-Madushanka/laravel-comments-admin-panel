@php use Illuminate\Support\Str; @endphp
<div>
    <x-slot:linearNavigation>
        <x-filament::breadcrumbs :breadcrumbs="[
                route('admin.comments.dashboard') => 'Dashboard',
                route('admin.models.comments.index', ['name' => str($modelName)->replace('\\', '')->kebab()->plural()]) =>
                 \str($modelName)->plural()->kebab()->title(),
                route('admin.models.comments.index', ['name' => str($modelName)->replace('\\', '')->kebab()->plural()]) . '#' => $modelId,
                route('admin.models.comments.show', ['modelName' => str($modelName)->replace('\\', '')->kebab()->plural(), 'id' => $modelId]) . '#' => 'Comments',
                url()->current() => $comment->getKey(),
                url()->current() . '#' => 'Edit',
            ]"
        />
    </x-slot:linearNavigation>

    <x-slot:header>
        <h1>Edit Comment Id &rarr; {{$comment->getKey()}}</h1>
    </x-slot:header>

    <form class="shadow p-2" wire:submit="save">
        {{ $this->form }}

        <div class="my-4">
            <x-comments-admin-panel::button type="submit">
                Submit
            </x-comments-admin-panel::button>
        </div>
    </form>

    <x-filament-actions::modals/>

</div>
