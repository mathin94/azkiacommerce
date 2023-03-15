<div @class([
    'filament-brand text-xl font-bold tracking-tight',
    'dark:text-white' => config('filament.dark_mode'),
])>
    <img src="{{ asset('static/main-logo.png') }}" alt="{{ config('app.name') }} Logo"
        style="border-radius: 50%; width: 130px; height: 130px;" class="mb-3">
</div>
