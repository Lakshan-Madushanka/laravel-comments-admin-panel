@php use Illuminate\Support\Facades\Route;use Illuminate\Support\Str; @endphp
<div>
    <x-slot:header>
        <h1>{{Str::plural($modelName)}}</h1>
    </x-slot:header>

    <x-slot:linearNavigation>
        <x-filament::breadcrumbs :breadcrumbs="[
                route('admin.comments.dashboard') => 'Dashboard',
                url()->current() => $modelName
            ]"
        />
    </x-slot:linearNavigation>
    <div>
        {{$this->table}}
    </div>
</div>
