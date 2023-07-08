<div>
    <main class="main">
        <nav aria-label="breadcrumb" class="breadcrumb-nav mb-0 border-0">
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                </ol>
            </div><!-- End .container -->
        </nav>
        <div class="page-content">
            <div class="container">
                @if (!blank($category->catalog_image_url))
                <figure class="entry-media">
                    <img src="{{ $category->catalog_image_url }}" alt="image {{ $category->name }}">
                </figure>
                @endif

                <div class="toolbox">
                    <div class="toolbox-right">
                        <div class="toolbox-sort">
                            <label for="sortby">Urutkan:</label>
                            <div class="select-custom">
                                <select wire:model="sortBy" class="form-control">
                                    <option value="created_at">Terbaru</option>
                                    <option value="top_rated">Paling Populer</option>
                                    <option value="name_asc">Nama (A-Z)</option>
                                    <option value="name_desc">Nama (Z-A)</option>
                                    <option value="lowest_price">Harga Terendah</option>
                                    <option value="highest_price">Harga Tertinggi</option>
                                </select>
                            </div>
                        </div><!-- End .toolbox-sort -->
                    </div><!-- End .toolbox-right -->
                </div>

                <div class="products">
                    <div class="row" wire:loading.class="d-none" wire:target="getProducts">
                        @foreach ($products as $item)
                        <div class="col-6 col-md-4 col-lg-4 col-xl-3">
                            <div class="product product-2">
                                <figure class="product-media">
                                    @if ($item->discount_percentage > 0)
                                    <span class="product-label label-sale">Diskon {{ $item->discount_percentage }}%</span>
                                    @endif
                                    <a href="{{ $item->public_url }}">
                                        <img src="{{ $item->main_image_url }}" alt="{{ $item->name }} image" class="product-image">
                                    </a>

                                    <livewire:wishlist-button :product=$item />

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
                                        @if ($item->activeDiscount)
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
            </div>
        </div>
    </main>
</div>
