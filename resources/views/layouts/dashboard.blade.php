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
    @vite(['resources/sass/app.scss'])

    @stack('styles')
</head>

<body>
    @livewireScripts
    <livewire:partials.navbar />
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
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Pesan
                                        Instant</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Keluar</a>
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
    <script src="{{ asset('build/assets/virtual-select-plugin/dist/virtual-select.min.js') }}"></script>
    @vite('resources/js/app.js')
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
    </script>
</body>

</html>
