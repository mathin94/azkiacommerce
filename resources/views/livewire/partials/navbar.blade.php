<div>
    @if ($head_notification)
        <div class="notification {{ $head_notification['class'] ?? '' }}">
            <div class="notify-content">
                <h3>{!! $head_notification['content'] !!}</h3>
            </div>
            <div class="notify-action">
                <a href="#" id="head-notification-close"><i class="icon-close"></i></a>
            </div>
        </div>
    @endif
    <header class="header">
        <div class="header-top">
            <div class="container">
                <div class="header-right">
                    <ul class="top-menu pt-1 pb-0">
                        <li><a href="#">Menu</a>
                            <ul>
                                @if (auth()->guard('shop')->check())
                                    <li class="hide-on-mobile">
                                        <a href="{{ route('customer.wishlist') }}">
                                            <i class="icon-heart-o"></i> Wishlist
                                            <span class="wishlist-count">
                                                {{ $wishlist_count }}
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('customer.orders') }}">
                                            <i class="icon-shopping-cart"></i> Pesanan Saya
                                            <span class="wishlist-count">
                                                {{-- {{ $wishlist_count }} --}}
                                            </span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('customer.dashboard') }}"><i class="icon-user"></i> Akun
                                            Saya</a>
                                    </li>
                                    <li>
                                        <a wire:click="openLogoutModal" style="cursor: pointer">
                                            <i class="fa fa-sign-out"></i>
                                            Keluar
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ route('login') }}">
                                            <i class="icon-user"></i>
                                            Masuk / Daftar
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    </ul>
                </div>
            </div><!-- End .container -->
        </div><!-- End .header-top -->
        <div class="header-middle sticky-header">
            <div class="container navbar-container">
                <div class="header-left">
                    <button class="mobile-menu-toggler">
                        <span class="sr-only">Toggle mobile menu</span>
                        <i class="icon-bars"></i>
                    </button>

                    <a href="/" class="logo">
                        <img src="{{ site()->mainLogo() }}" alt="{{ site()->siteTitle() }}" width="80" height="20" class="desktop-logo">
                        <img src="{{ site()->mobileLogo() }}" alt="{{ site()->siteTitle() }}" width="70" height="15" class="mobile-logo">
                    </a>

                    <nav class="main-nav">
                        {!! $navbar_menu !!}
                    </nav><!-- End .main-nav -->
                </div><!-- End .header-left -->

                <div class="header-right">
                    <div class="header-search">
                        <a href="#" class="search-toggle" role="button" title="Search"><i
                                class="icon-search"></i></a>
                        <form action="{{ route('products.index') }}" method="get">
                            <div class="header-search-wrapper">
                                <label for="q" class="sr-only">Search</label>
                                <input type="search" class="form-control" name="search"
                                    placeholder="Cari Produk..." required>
                            </div><!-- End .header-search-wrapper -->
                        </form>
                    </div><!-- End .header-search -->
                    <div class="dropdown cart-dropdown">
                        <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false" data-display="static">
                            <i class="icon-shopping-cart"></i>
                            <span class="cart-count">{{ $cartCount ?? 0 }}</span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="dropdown-cart-products">
                                @if ($cartCount > 0)
                                    @foreach ($cartItems as $item)
                                        <div class="product">
                                            <div class="product-cart-details">
                                                <h4 class="product-title">
                                                    <a href="product.html">{{ $item->name }}</a>
                                                </h4>

                                                <span class="cart-product-info">
                                                    <span class="cart-product-qty">{{ $item->quantity }}</span>
                                                    x {{ $item->price_label }}
                                                </span>
                                            </div><!-- End .product-cart-details -->

                                            <figure class="product-image-container">
                                                <a href="product.html" class="product-image">
                                                    <img src="{{ $item->product_image_url }}"
                                                        alt="{{ $item->name }}">
                                                </a>
                                            </figure>
                                            <a href="javascript:void(0);"
                                            wire:click="deleteItem({{ $item->id }})"
                                            class="btn-remove" title="Hapus Produk"><i
                                                    class="icon-close"></i></a>
                                        </div><!-- End .product -->
                                    @endforeach
                                @else
                                    <div class="product">
                                        <span>Belum ada produk</span>
                                    </div>
                                @endif
                            </div><!-- End .cart-product -->

                            <div class="dropdown-cart-total">
                                <span>Total</span>

                                <span class="cart-total-price">{{ $cartTotal }}</span>
                            </div><!-- End .dropdown-cart-total -->

                            @if ($cartCount > 0)
                                <div class="dropdown-cart-action">
                                    <a href="{{ route('cart') }}" class="btn btn-primary">Lihat Keranjang</a>
                                </div><!-- End .dropdown-cart-total -->
                            @endif
                        </div><!-- End .dropdown-menu -->
                    </div><!-- End .cart-dropdown -->
                </div><!-- End .header-right -->
            </div><!-- End .container -->
        </div><!-- End .header-middle -->
    </header><!-- End .header -->

    <div id="logout-confirm-dialog" class="white-popup mfp-hide">
        <div class="text-center">
            <h5>Yakin untuk keluar ?</h5>
            <button type="button" class="btn btn-danger" id="logout-confirm-button" wire:click="logout">
                Ya, Keluar
            </button>
            <button type="button" class="btn btn-outline-dark" id="cancel-logout" onclick="$.magnificPopup.close()">
                Batalkan
            </button>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $('#logout-confirmation-button').click(function(e) {
            Livewire.emit('logout');
            $.magnificPopup.close();
        });

        Livewire.on('open-logout-dialog', function() {
            $.magnificPopup.open({
                items: {
                    src: '#logout-confirm-dialog',
                    type: 'inline'
                }
            });
        })

        $('.notify-action #head-notification-close').on('click', function() {
            $('.notification').css('display', 'none');
        })
    </script>
@endpush
