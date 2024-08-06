@php use Illuminate\Support\Facades\Auth; @endphp
<div
    x-data="{show: true}"
    x-init="window.innerWidth <=1024 ? show=false : ''"
    x-cloak
    @click.outside="window.innerWidth <=1024 ? show=false : ''; $dispatch('sidebar-opened', {status: false})"
    class="h-full rounded border-r"
>
    <div
        x-show="!show"
        @click="show=true; $dispatch('sidebar-opened', {status: true})"
        class="fixed top-1 left-2 z-10 cursor-pointer">
        <x-comments-admin-panel::icons.align-justify/>
    </div>

    <div
        x-show="show"
        x-transition
        class="p-4 min-w-[100vw] sm:min-w-64 sm:max-w-64"
    >
        <div
             @click="show=false; $dispatch('sidebar-opened', {status: false})"
             class="flex justify-between items-center cursor-pointer z-10"
        >
            <a wire:navigate href="/"><x-comments-admin-panel::icons.home /></a>
            <x-comments-admin-panel::icons.close/>
        </div>

       <div class="flex flex-col mt-4">
           <div
               wire:click="go('{{route('admin.comments.dashboard')}}')"
               class="bg-gray-200 p-2 mt-4 rounded cursor-pointer hover:bg-gray-300"
               :class="'{{route('admin.comments.dashboard')}}' === window.location.href ? 'border-l-4 border-gray-500' : ''"
           >
               <span>Dashboard</span>
           </div>
           <div class="font-bold text-lg mt-4 mb-2">Models</div>
           @foreach($models as $key => $model)
                <div
                    wire:click="navigate('{{$key}}')"
                    class="bg-gray-200 p-2 mb-4 flex gap-x-2 justify-between items-center rounded cursor-pointer hover:bg-gray-300"
                    :class="'{{$this->getModelRoute($key)}}' === window.location.href ? 'border-l-4 border-gray-500' : ''"
                    :key="$key"
                >
                    <div class="!w-[85%] overflow-auto">{!! str($key)->replace('\\', "&rarr;")->plural() !!}</div>
                    <div class="flex-1"><x-comments-admin-panel::chip class="text-sm">{{$model['count']}}</x-comments-admin-panel::chip></div>
                </div>
           @endforeach
       </div>
    </div>
</div>

