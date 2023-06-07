<div>
    <nav class="filament-tabs flex overflow-x-auto items-center p-1 space-x-1 rtl:space-x-reverse text-sm text-gray-600">
        @foreach ($menus as $key => $name)
            <button type="button" wire:click="$emit('setStatus', '{{ $key }}')"
                class="
                    filament-tabs-item flex items-center h-8 px-5 font-medium outline-none
                    @if ($status === $key) text-primary-600 border-b border-primary-600
                    @else
                        dark:text-gray-400 dark:hover:text-gray-300 dark:focus:text-primary-400 @endif
                ">
                {{ $name }}
            </button>
        @endforeach
    </nav>
</div>
