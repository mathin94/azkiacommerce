@props([
    'title' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title wire:loading.remove>{{ $title ? "{$title} - " : null }} {{ config('app.brand') }}</title>

    @livewireStyles
    @vite('resources/sass/app.scss')
    @stack('styles')
</head>

<body>
    @livewireScripts
    <livewire:partials.navbar />
    {{ $slot }}
    <x-app-footer />
    <button id="scroll-top" title="Back to Top"><i class="icon-arrow-up"></i></button>
    <x-app-modals />
    <!-- Plugins JS File -->
    <script src="{{ asset('build/assets/js/jquery.min.js') }}"></script>
    <script src="{{ asset('build/assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('build/assets/js/jquery.hoverIntent.min.js') }}"></script>
    <script src="{{ asset('build/assets/js/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('build/assets/js/superfish.min.js') }}"></script>
    <script src="{{ asset('build/assets/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('build/assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('build/assets/js/jquery.plugin.min.js') }}"></script>
    <script src="{{ asset('build/assets/js/jquery.countdown.min.js') }}"></script>

    <!-- Main JS File -->
    <script src="{{ asset('build/assets/js/main.js') }}"></script>
    <script src="{{ asset('build/assets/js/demos/demo-5.js') }}"></script>

    @stack('scripts')
</body>

</html>
