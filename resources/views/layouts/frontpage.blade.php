@props([
    'title' => null,
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <meta name="author" content="{{ site()->site_name }}">
    <meta property="og:title" content="{{ $title ? "{$title} - " : null }} {{ app(App\Settings\SiteSettings::class)->siteTitle() }}">
    <meta name="twitter:title" content="{{ $title ? "{$title} - " : null }} {{ app(App\Settings\SiteSettings::class)->siteTitle() }}">
    @stack('meta')

    <title wire:loading.remove>{{ $title ? "{$title} - " : null }} {{ app(App\Settings\SiteSettings::class)->siteTitle() }}</title>
    <link rel="shortcut icon" href="{{ site()->favicon() }}" type="image/x-icon">
    @livewireStyles
    <fc:styles />
    @vite('resources/sass/app.scss')
    @stack('styles')
</head>

<body>


    <div><livewire:partials.navbar /></div>
    {{ $slot }}
    <div class="white-popup d-none" id="alert-popup">
        <h5 id="alert-title"></h5>
        <div id="alert-body"></div>
    </div>
    <livewire:partials.footer />
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
    <script src="https://cdn.jsdelivr.net/npm/sharer.js@latest/sharer.min.js"></script>
    @vite('resources/js/app.js')
    @livewireScripts
    <fc:scripts />
    @stack('scripts')

    {!! site()->custom_scripts !!}

    <script type="text/javascript">
        Livewire.on('showAlert', data => {
            $.magnificPopup.open({
                items: {
                    src: data.alert,
                    type: 'inline'
                },
                closeOnContentClick: true,
                closeOnBgClick: true,
                showCloseBtn: true
            });
        })
    </script>
</body>

</html>
