@props(['heading' => '', 'subheading' => '', 'showNavigation' => true])

<div class="flex items-start max-md:flex-col">
    @if($showNavigation)
        <div class="me-10 w-full pb-4 md:w-[220px]">
            <flux:navlist>
                <flux:navlist.item icon="squares-plus" :href="route('plants.index')" wire:navigate>
                    {{ __('Alle Pflanzen') }}
                </flux:navlist.item>

                @can('create', App\Models\Plant::class)
                    <flux:navlist.item icon="plus" :href="route('plants.create')" wire:navigate>
                        {{ __('Neue Pflanze') }}
                    </flux:navlist.item>
                @endcan

                @cannot('create', App\Models\Plant::class)
                    <flux:navlist.item icon="paper-airplane" :href="route('plants.request')" wire:navigate>
                        {{ __('Pflanze vorschlagen') }}
                    </flux:navlist.item>
                @endcannot

                @can('viewAny', App\Models\PlantRequest::class)
                    <flux:navlist.item icon="cog-6-tooth " :href="route('admin.dashboard')" wire:navigate>
                        {{ __('Admin Dashboard') }}
                    </flux:navlist.item>
                @endcan
            </flux:navlist>
        </div>

        <flux:separator class="md:hidden"/>
    @endif

    <div class="flex-1 self-stretch max-md:pt-6">
        @if($heading)
            <flux:heading>{{ $heading }}</flux:heading>
        @endif

        @if($subheading)
            <flux:subheading>{{ $subheading }}</flux:subheading>
        @endif

        <div class="mt-5 w-full">
            {{ $slot }}
        </div>
    </div>
</div>
