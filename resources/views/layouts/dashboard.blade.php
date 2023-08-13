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
    <link rel="shortcut icon" href="{{ site()->favicon() }}" type="image/x-icon">
    @livewireStyles
    <fc:styles />
    @vite(['resources/sass/app.scss'])

    <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-star-rating@4.1.2/css/star-rating.min.css" media="all"
        rel="stylesheet" type="text/css" />

    <!-- with v4.1.0 Krajee SVG theme is used as default (and must be loaded as below) - include any of the other theme CSS files as mentioned below (and change the theme property of the plugin) -->
    <link href="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-star-rating@4.1.2/themes/krajee-svg/theme.css" media="all"
        rel="stylesheet" type="text/css" />

    @stack('styles')
</head>

<body>
    <div><livewire:partials.navbar /></div>
    <main class="main">
        <nav aria-label="breadcrumb" class="breadcrumb-nav mb-3">
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Akun Saya</li>
                </ol>
            </div><!-- End .container -->
        </nav><!-- End .breadcrumb-nav -->

        <div class="page-content">
            <div class="dashboard">
                <div class="container">
                    <div class="row">
                        <aside class="col-md-4 col-lg-3">
                            <ul class="nav nav-dashboard flex-column mb-3 mb-md-0" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}"
                                        href="{{ route('customer.dashboard') }}">Dashboard</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('customer.profile') ? 'active' : '' }}"
                                        href="{{ route('customer.profile') }}">Profil</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('customer.addresses') ? 'active' : '' }}"
                                        href="{{ route('customer.addresses') }}" role="tab">Alamat</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('customer.wishlist') ? 'active' : '' }}"
                                        href="{{ route('customer.wishlist') }}">Wishlist</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('customer.orders') ? 'active' : '' }}"
                                        href="{{ route('customer.orders') }}">Pesanan
                                        Saya</a>
                                </li>
                                @if (auth()->user()->is_agen || auth()->user()->is_distributor)
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('cart.instant-order') ? 'active' : '' }}" href="{{ route('cart.instant-order') }}">Pesan Langsung</a>
                                </li>
                                @endif
                                <li class="nav-item">
                                    <a class="nav-link" href="javascript:void(0);" onclick="logout()">Keluar</a>
                                </li>
                            </ul>
                        </aside><!-- End .col-lg-3 -->

                        <div class="col-md-8 col-lg-9">
                            <div class="tab-content">
                                {{ $slot }}
                            </div>
                        </div><!-- End .col-lg-9 -->
                    </div><!-- End .row -->
                </div><!-- End .container -->
            </div><!-- End .dashboard -->
        </div><!-- End .page-content -->
    </main><!-- End .main -->
    <livewire:partials.footer />
    <button id="scroll-top" title="Back to Top"><i class="icon-arrow-up"></i></button>
    <x-app-modals />
    <!-- Plugins JS File -->
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/gh/livewire/turbolinks@v0.1.x/dist/livewire-turbolinks.js" data-turbolinks-eval="false" data-turbo-eval="false"></script>
    @vite('resources/js/app.js')
    <fc:scripts />
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
    <script src="{{ asset('build/assets/virtual-select-plugin/dist/virtual-select.min.js') }}"></script>
    @vite('resources/js/app.js')

    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-star-rating@4.1.2/js/star-rating.min.js"
        type="text/javascript"></script>

    <!-- with v4.1.0 Krajee SVG theme is used as default (and must be loaded as below) - include any of the other theme JS files as mentioned below (and change the theme property of the plugin) -->
    <script src="https://cdn.jsdelivr.net/gh/kartik-v/bootstrap-star-rating@4.1.2/themes/krajee-svg/theme.js"></script>

    @stack('scripts')

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

        function logout() {
            Livewire.emit('open-logout-modal');
        }

        $(document).ready(function () {
            $('.rating-input').rating();
        });
    </script>
</body>

</html>
