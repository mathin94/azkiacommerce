@props([
    'title' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ? "{$title} - " : null }} {{ config('app.brand') }}</title>
    <link rel="shortcut icon" href="{{ site()->favicon() }}" type="image/x-icon">
    @livewireStyles
    @vite('resources/sass/app.scss')
    @stack('styles')
</head>

<body>
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/gh/livewire/turbolinks@v0.1.x/dist/livewire-turbolinks.js" data-turbolinks-eval="false" data-turbo-eval="false"></script>
    @vite('resources/js/app.js')
    {{ $slot }}

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
    <script src="https://cdn.jsdelivr.net/npm/sharer.js@latest/sharer.min.js"></script>
    @stack('scripts')
</body>

</html>
