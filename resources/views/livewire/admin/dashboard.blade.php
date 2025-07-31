@php use Illuminate\Support\Str; @endphp
<div class="space-y-6">
    <x-slot:header>
        <h1 class="text-center lg:text-left">
            <span>Commentable Models</span>
            <x-comments-admin-panel::chip class="!inline !px-2 !py-0 bg-blue-500">{{count($models)}}</x-comments-admin-panel::chip>
        </h1>
    </x-slot:header>

    <div class="flex gap-x-12 gap-y-4 flex-wrap">
        @foreach($models as $key => $model)
            <div class="flex flex-col gap-y-4 rounded border w-full lg:w-auto lg:min-w-96 p-2 shadow">
                <div class="flex justify-between">
                    <span class="font-bold text-lg">{!! Str::replace('\\', "&rarr;", $key) !!}</span>
                    <span wire:click="navigate('{{$key}}')" class="hover:cursor-pointer"><x-heroicon-c-eye class="h-6 w-6 text-blue-600" /></span>
                </div>
                <div class="flex justify-between items-center">
                    <span>Total Comments</span>
                    <x-comments-admin-panel::chip class="!px-2 !py-0 bg-green-500">{{$model['count']}}</x-comments-admin-panel::chip>
                </div>
                <div class="flex justify-between items-center">
                    <span>Mode</span>
                    <x-comments-admin-panel::chip class="!px-2 !py-0 bg-green-500">{{$model['instance']->guestModeEnabled() ? 'Guest' : 'Auth'}}</x-comments-admin-panel::chip>
                </div>
                <div class="flex justify-between items-center">
                    <span>Approval Required</span>
                    <x-comments-admin-panel::chip class="!px-2 !py-0 bg-green-500">{{$model['instance']->approvalRequired() ? 'Yes' : 'No'}}</x-comments-admin-panel::chip>
                </div>
                <div class="flex justify-between items-center">
                    <span>Limits Per User</span>
                    <x-comments-admin-panel::chip class="!px-2 !py-0 bg-green-500">{{is_null(config('commenter.limit')) ? 'Unlimited' : config('commenter.limit')}}</x-comments-admin-panel::chip>
                </div>
                <div class="flex justify-between items-center">
                    <span>Repliable</span>
                    <x-comments-admin-panel::chip class="!px-2 !py-0 bg-green-500">{{config('commenter.reply.enabled') ? 'True' : 'False'}}</x-comments-admin-panel::chip>
                </div>
            </div>
        @endforeach
    </div>
</div>
