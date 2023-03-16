<x-base-layout>
    <x-app-navbar />
    {{ $slot }}
    <x-app-footer />
    <button id="scroll-top" title="Back to Top"><i class="icon-arrow-up"></i></button>
    <x-app-modals />
</x-base-layout>
