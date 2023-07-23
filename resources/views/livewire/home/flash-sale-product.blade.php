<div>
    @if ($products)
    <div class="container recent-arrivals">
        <div class="heading heading-flex align-items-center mb-3">
            <h3 class="title text-center mb-3">FLASH SALE</h3>
        </div><!-- End .heading -->

        <div class="content">
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
                @forelse ($products as $item)
                    <livewire:product-figure :product=$item />
                @empty
                    Belum ada produk
                @endforelse
            </div><!-- End .owl-carousel -->
        </div><!-- End .tab-content -->

        <div class="more-container text-center mt-3 mb-3">
            <a href="{{ route('products.index') }}" class="btn btn-outline-dark-3 btn-more"><span>Tampilkan Selengkapnya</span><i
                    class="icon-long-arrow-right"></i></a>
        </div><!-- End .more-container -->
    </div><!-- End .container -->
    @endif
</div>
