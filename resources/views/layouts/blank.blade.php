<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ? "{$title} - " : null }} {{ config('app.brand') }}</title>
    <link rel="shortcut icon" href="{{ site()->favicon() }}" type="image/x-icon">
</head>
@livewireStyles

@stack('styles')
<body>
    {{ $slot }}
    @livewireScripts
    @vite('resources/js/app.js')
    @stack('scripts')
</body>
</html>
