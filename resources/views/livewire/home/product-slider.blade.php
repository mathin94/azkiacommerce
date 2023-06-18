<div class="container">
    <ul class="nav nav-pills nav-big nav-border-anim justify-content-center mb-2 mb-md-3" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="products-featured-link" data-toggle="tab" href="#products-featured-tab"
                role="tab" aria-controls="products-featured-tab" aria-selected="true">Produk Unggulan</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="products-sale-link" data-toggle="tab" href="#products-sale-tab" role="tab"
                aria-controls="products-sale-tab" aria-selected="false">Sedang Diskon</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="products-top-link" data-toggle="tab" href="#products-top-tab" role="tab"
                aria-controls="products-top-tab" aria-selected="false">Paling Laris</a>
        </li>
    </ul>

    <div class="tab-content tab-content-carousel">
        <div class="tab-pane p-0 fade show active" id="products-featured-tab" role="tabpanel"
            aria-labelledby="products-featured-link">
            <div class="owl-carousel owl-simple carousel-equal-height carousel-with-shadow" data-toggle="owl"
                data-owl-options='{
                                "nav": false,
                                "dots": true,
                                "margin": 20,
                                "loop": false,
                                "responsive": {
                                    "0": {
                                        "items":2
                                    },
                                    "480": {
                                        "items":2
                                    },
                                    "768": {
                                        "items":3
                                    },
                                    "992": {
                                        "items":4
                                    },
                                    "1200": {
                                        "items":4,
                                        "nav": true,
                                        "dots": false
                                    }
                                }
                            }'>
                @forelse ($featuredProducts as $item)
                    <div class="product product-2">
                        <figure class="product-media">
                            @if ($item->discount_percentage > 0)
                            <span class="product-label label-sale">Diskon {{ $item->discount_percentage }}%</span>
                            @endif
                            <a href="{{ $item->public_url }}">
                                <img src="{{ $item->main_image_url }}" alt="{{ $item->name }} image" class="product-image">
                            </a>

                            <div class="product-action-vertical">
                                <a href="#" class="btn-product-icon btn-wishlist btn-expandable">
                                    <span>tambahkan ke wishlist</span></a>
                            </div><!-- End .product-action -->

                            {{-- <div class="product-action action-icon-top">
                                <a href="#" class="btn-product btn-cart">
                                    <span>Tambahkan Ke Keranjang</span></a>
                            </div><!-- End .product-action --> --}}
                        </figure>
                    </div>
                @empty
                    Belum ada produk
                @endforelse
            </div><!-- End .owl-carousel -->
        </div><!-- .End .tab-pane -->
        <div class="tab-pane p-0 fade" id="products-sale-tab" role="tabpanel" aria-labelledby="products-sale-link">
            <div class="owl-carousel owl-simple carousel-equal-height carousel-with-shadow" data-toggle="owl"
                data-owl-options='{
                                "nav": false,
                                "dots": true,
                                "margin": 20,
                                "loop": false,
                                "responsive": {
                                    "0": {
                                        "items":1
                                    },
                                    "480": {
                                        "items":2
                                    },
                                    "768": {
                                        "items":3
                                    },
                                    "992": {
                                        "items":4
                                    },
                                    "1200": {
                                        "items":4,
                                        "nav": true,
                                        "dots": false
                                    }
                                }
                            }'>
                @forelse ($onSaleProducts as $item)
                    <div class="product product-2">
                        <figure class="product-media">
                            @if ($item->discount_percentage > 0)
                            <span class="product-label label-sale">Diskon {{ $item->discount_percentage }}%</span>
                            @endif
                            <a href="{{ $item->public_url }}">
                                <img src="{{ $item->main_image_url }}" alt="{{ $item->name }} image" class="product-image">
                            </a>

                            <div class="product-action-vertical">
                                <a href="#" class="btn-product-icon btn-wishlist btn-expandable">
                                    <span>tambahkan ke wishlist</span></a>
                            </div><!-- End .product-action -->

                            {{-- <div class="product-action action-icon-top">
                                <a href="#" class="btn-product btn-cart">
                                    <span>Tambahkan Ke Keranjang</span></a>
                            </div><!-- End .product-action --> --}}
                        </figure>
                    </div>
                @empty
                    Belum ada produk
                @endforelse
            </div><!-- End .owl-carousel -->
        </div><!-- .End .tab-pane -->
        <div class="tab-pane p-0 fade" id="products-top-tab" role="tabpanel" aria-labelledby="products-top-link">
            <div class="owl-carousel owl-simple carousel-equal-height carousel-with-shadow" data-toggle="owl"
                data-owl-options='{
                                "nav": false,
                                "dots": true,
                                "margin": 20,
                                "loop": false,
                                "responsive": {
                                    "0": {
                                        "items":1
                                    },
                                    "480": {
                                        "items":2
                                    },
                                    "768": {
                                        "items":3
                                    },
                                    "992": {
                                        "items":4
                                    },
                                    "1200": {
                                        "items":4,
                                        "nav": true,
                                        "dots": false
                                    }
                                }
                            }'>

                @forelse ($topRatedProducts as $item)
                    <div class="product product-2">
                        <figure class="product-media">
                            @if ($item->discount_percentage > 0)
                                <span class="product-label label-sale">Diskon {{ $item->discount_percentage }}%</span>
                            @endif
                            <a href="{{ $item->public_url }}">
                                <img src="{{ $item->main_image_url }}" alt="{{ $item->name }} image" class="product-image">
                            </a>

                            <div class="product-action-vertical">
                                <a href="#" class="btn-product-icon btn-wishlist btn-expandable">
                                    <span>tambahkan ke wishlist</span></a>
                            </div><!-- End .product-action -->

                            {{-- <div class="product-action action-icon-top">
                                <a href="#" class="btn-product btn-cart">
                                    <span>Tambahkan Ke Keranjang</span></a>
                            </div><!-- End .product-action --> --}}
                        </figure>
                    </div>
                @empty
                    Belum ada produk
                @endforelse
            </div><!-- End .owl-carousel -->
        </div><!-- .End .tab-pane -->
    </div><!-- End .tab-content -->
</div><!-- End .container -->
