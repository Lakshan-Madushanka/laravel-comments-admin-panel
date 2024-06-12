<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf_token" value="{{ csrf_token() }}"/>

    <title>{{ $title ?? 'Comments' }}</title>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>

    @filamentStyles

    {{
       Vite::useHotFile('vendor/lakm/comments-admin-panel/comments-admin-panel.hot')
           ->useBuildDirectory("vendor/lakm/comments-admin-panel/build")
           ->withEntryPoints(['resources/js/app.js'])
   }}
</head>

<body>
<div
    x-data="{sidebarOpened: false}"
    @sidebar-opened.window="sidebarOpened = $event.detail.status"
    class="flex min-h-screen overflow-hidden"
>
    <aside>
        <livewire:comments-admin-sidebar/>
    </aside>

    <div
        class="flex-1 p-6 overflow-auto lg:bg-white transition"
        :class="sidebarOpened ? 'bg-[rgba(9,9,11,.5)]' : 'bg-white opacity-100'"
    >
        @if(isset($linearNavigation))
            <div>
                {{$linearNavigation}}
            </div>
        @endif

        <header class="my-6">{{$header}}</header>

        <main>
            {{ $slot }}
        </main>
    </div>
</div>
@livewire('notifications')
@filamentScripts
</body>
</html>
