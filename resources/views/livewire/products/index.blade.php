<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav mb-2">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
            </ol>
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->

    <div class="page-content">
        <div class="container">
            <div class="toolbox">
                <div class="toolbox-left">
                    <a href="#" class="sidebar-toggler"><i class="icon-bars"></i>Filter Data</a>
                </div><!-- End .toolbox-left -->

                <div class="toolbox-right">
                    <div class="toolbox-sort">
                        <label for="sortby">Urutkan:</label>
                        <div class="select-custom">
                            <x-select
                                name="sortBy"
                                wire:model.lazy="sortBy"
                                :options="[
                                    'created_at' => 'Terbaru',
                                    'top_rated' => 'Paling Populer',
                                    'name_asc' => 'Nama (A-Z)',
                                    'name_desc' => 'Nama (Z-A)',
                                    'lowest_price' => 'Harga Terendah',
                                    'highest_price' => 'Harga Tertinggi',
                                ]"
                                class="form-control"
                            />
                        </div>
                    </div><!-- End .toolbox-sort -->
                </div><!-- End .toolbox-right -->
            </div><!-- End .toolbox -->

            <div class="products">
                <div class="row">
                    @foreach ($products as $item)
                    <div class="col-6 col-md-4 col-lg-4 col-xl-3">
                        <div class="product product-1">
                            <figure class="product-media">
                                @if ($item->discount_percentage > 0)
                                <span class="product-label label-sale">Diskon {{ $item->discount_percentage }}%</span>
                                @endif
                                <a href="{{ $item->public_url }}">
                                    <img src="{{ $item->main_image_url }}" alt="{{ $item->name }} image" class="product-image">
                                </a>

                                <livewire:wishlist-button :product=$item :key="'item-'.$item->id"/>

                                {{-- <div class="product-action action-icon-top">
                                    <a href="#" class="btn-product btn-cart">
                                        <span>Tambahkan Ke Keranjang</span></a>
                                </div><!-- End .product-action --> --}}
                            </figure>

                            <div class="product-body">
                                <div class="product-cat">
                                    <a href="{{ $item->category->public_url }}">{{ $item->category_name }}</a>
                                </div><!-- End .product-cat -->
                                <h3 class="product-title"><a href="{{ $item->public_url }}">{{ $item->name }}</a>
                                </h3>
                                <!-- End .product-title -->
                                <div class="product-price">
                                    @if ($item->discount_percentage > 0)
                                        <span class="old-price">{{ $item->normal_price_label }}</span>
                                    @endif
                                    <span class="new-price">{{ $item->price_label }}</span>
                                </div><!-- End .product-price -->
                                <div class="ratings-container">
                                    <div class="ratings">
                                        <div class="ratings-val" style="width: {{ $item->rating_percentage }}%;"></div><!-- End .ratings-val -->
                                    </div><!-- End .ratings -->
                                    <span class="ratings-text">( {{ $item->review_count }} Ulasan )</span>
                                </div><!-- End .rating-container -->
                            </div>
                        </div>
                    </div><!-- End .col-sm-6 col-lg-4 col-xl-3 -->
                    @endforeach
                </div><!-- End .row -->

                <div class="load-more-container text-center">
                    {!! $products->links() !!}
                </div><!-- End .load-more-container -->
            </div>

            <livewire:product-filter />
        </div><!-- End .container -->
    </div><!-- End .page-content -->
</main>
