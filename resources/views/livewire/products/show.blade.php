<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
        <div class="container d-flex align-items-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
            </ol>
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->

    <div class="page-content">
        <div class="container">
            <div class="product-details-top">
                <div class="row">
                    <div class="col-md-6">
                        <div class="product-gallery product-gallery-horizontal p-3">
                            <div class="row">
                                <figure class="product-main-image">
                                    <img id="product-zoom" src="{{ $product->main_image_url }}"
                                        data-zoom-image="{{ $product->main_image_url }}" alt="product image">

                                    <a href="#" id="btn-product-gallery" class="btn-product-gallery">
                                        <i class="icon-arrows"></i>
                                    </a>
                                </figure><!-- End .product-main-image -->

                                <div id="product-zoom-gallery" wire:ignore
                                    class="product-image-gallery owl-carousel owl-simple carousel-equal-height carousel-with-shadow p-2">
                                    @foreach ($product->media as $item)
                                        <figure class="product-media">
                                            <a href="#" data-image="{{ $item->getUrl() }}"
                                                data-zoom-image="{{ $item->getUrl() }}">
                                                <img src="{{ $item->getUrl() }}" alt="Product image"
                                                    class="product-image">
                                            </a>
                                        </figure><!-- End .product-media -->
                                    @endforeach
                                </div><!-- End .product-image-gallery -->

                            </div><!-- End .row -->
                        </div><!-- End .product-gallery -->
                    </div><!-- End .col-md-6 -->

                    <div class="col-md-6">
                        <div class="product-details">
                            <h1 class="product-title">{{ $product->name }}</h1>
                            <!-- End .product-title -->

                            <div class="ratings-container">
                                <div class="ratings">
                                    <div class="ratings-val" style="width: {{ $product->rating_percentage }}%;"></div><!-- End .ratings-val -->
                                </div><!-- End .ratings -->
                                <a class="ratings-text" href="#product-review-link" id="review-link">( {{ $product->review_count }} Ulasan
                                    )</a>
                            </div><!-- End .rating-container -->
                            @if ($normalPrice != $price)
                                <div class="product-price">
                                    <span class="old-price" wire:loading.class="d-none" wire:target="setSize,setColor">{{ $normalPrice }}</span>
                                    <x-css-spinner-alt wire:loading class="ml-1 fa-spin" wire:target="setSize,setColor" />
                                </div>
                            @endif
                            <div class="product-price">
                                <span class="new-price" wire:loading.class="d-none"
                                    wire:target="setSize,setColor">{{ $price }}</span>
                                <x-css-spinner-alt wire:loading class="ml-1 fa-spin" wire:target="setSize,setColor" />
                            </div><!-- End .product-price -->

                            <div class="product-content">
                                {!! \Str::of($product->seo->description)->limit(150) !!}

                                <br />
                                <label for="">Berat per produk: <span wire:loading.class="d-none"
                                        wire:target="setSize,setColor">{{ $weight }}</span>
                                    <x-css-spinner-alt wire:loading class="ml-1 fa-spin"
                                        wire:target="setSize,setColor" />
                                </label>
                            </div><!-- End .product-content -->

                            <div class="details-filter-row details-row-size">
                                <label for="color">Warna:</label>

                                <div class="btn-group-toggle" data-toggle="buttons">
                                    @foreach ($colors as $key => $value)
                                        <button wire:click="setColor({{ $key }})"
                                            class="btn btn-outline-dark selectable {{ $colorId === $key ? 'active' : '' }}">
                                            <input type="radio" name="colorId" wire:model="colorId"
                                                value="{{ $key }}">
                                            {{ \Str::of($value)->lower()->title() }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <div class="details-filter-row details-row-size">
                                <label for="size">Ukuran:</label> <br />

                                <div class="btn-group-toggle" data-toggle="buttons">
                                    @foreach ($sizes as $key => $value)
                                        <button wire:click="setSize({{ $key }})"
                                            class="btn btn-outline-dark selectable position-relative {{ $sizeId === $key ? 'active' : '' }}">
                                            <input type="radio">
                                            {{ $value }}
                                        </button>
                                    @endforeach
                                </div>
                            </div><!-- End .details-filter-row -->

                            <div class="details-filter-row details-row-size">
                                <label for="qty">Qty:</label>
                                <div class="product-details-quantity" wire:ignore>
                                    <input type="number" id="qty" class="form-control" min="1"
                                        wire:model="quantity">
                                </div><!-- End .product-details-quantity -->
                                <span class="ml-5" wire:loading.class="d-none" for="qty"
                                    wire:target="setSize,setColor">{{ $stockLabel }}</span>
                                <x-css-spinner-alt wire:loading class="ml-5 fa-spin" wire:target="setSize,setColor" />
                            </div><!-- End .details-filter-row -->

                            <div class="product-details-action">
                                <button wire:click="addToCart" class="btn-product btn-cart"
                                    @if ($stock < 1) disabled @endif>
                                    <span>Tambahkan ke keranjang</span>
                                </button>

                                <div class="details-action-wrapper">
                                    <a href="javascript:void(0);" wire:click="addToWishlist" class="btn-product"
                                        title="Tambahkan ke Wishlist">
                                        <span>
                                            @if ($liked)
                                                <i class="icon-heart"></i> Hapus Wishlist
                                            @else
                                                <i class="icon-heart-o"></i> Wishlist
                                            @endif
                                        </span>
                                    </a>
                                    <a href="#" class="btn-product btn-message" title="Order via Whatsapp">
                                        <span>
                                            <i class="icon-whatsapp"></i> Order via Whatsapp
                                        </span>
                                    </a>
                                </div><!-- End .details-action-wrapper -->
                            </div><!-- End .product-details-action -->

                            <div class="product-details-footer">
                                <div class="product-cat">
                                    <span>Kategori Produk:</span>
                                    <a href="#">{{ $product->category->name }}</a>
                                </div><!-- End .product-cat -->

                                <div class="social-icons social-icons-sm">
                                    <span class="social-label">Bagikan:</span>
                                    <a href="#" data-sharer="facebook"
                                        data-title="Beli {{ $product->name }} di {{ config('app.name') }}"
                                        data-url="{{ $product->public_url }}" class="social-icon"
                                        title="Facebook"><i class="icon-facebook-f"></i></a>
                                    <a href="#" data-sharer="twitter"
                                        data-title="Beli {{ $product->name }} di {{ config('app.name') }}"
                                        data-url="{{ $product->public_url }}" class="social-icon" title="Twitter"><i
                                            class="icon-twitter"></i></a>
                                    <a href="#" data-sharer="instagram"
                                        data-title="Beli {{ $product->name }} di {{ config('app.name') }}"
                                        data-url="{{ $product->public_url }}" class="social-icon"
                                        title="Instagram"><i class="icon-instagram"></i></a>
                                    <a href="#" data-sharer="pinterest"
                                        data-title="Beli {{ $product->name }} di {{ config('app.name') }}"
                                        data-url="{{ $product->public_url }}" class="social-icon"
                                        title="Pinterest"><i class="icon-pinterest"></i></a>
                                </div>
                            </div><!-- End .product-details-footer -->
                        </div><!-- End .product-details -->
                    </div><!-- End .col-md-6 -->
                </div><!-- End .row -->
            </div><!-- End .product-details-top -->

            <div class="product-details-tab">
                <ul class="nav nav-pills justify-content-center" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="product-desc-link" data-toggle="tab" href="#product-desc-tab"
                            role="tab" aria-controls="product-desc-tab" aria-selected="true">Deskripsi Produk</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="product-review-link" data-toggle="tab" href="#product-review-tab"
                            role="tab" aria-controls="product-review-tab" aria-selected="false">Ulasan Produk ({{ $product->review_count }})</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="product-desc-tab" role="tabpanel"
                        aria-labelledby="product-desc-link">
                        <div class="product-desc-content">
                            {!! $product->description !!}
                        </div><!-- End .product-desc-content -->
                    </div><!-- .End .tab-pane -->
                    <div class="tab-pane fade" id="product-review-tab" role="tabpanel"
                        aria-labelledby="product-review-link">
                        <div class="reviews">
                            <h3>Ulasan ({{ $product->review_count }})</h3>
                            @forelse ($reviews as $review)
                                <div class="review">
                                    <div class="row no-gutters">
                                        <div class="col-md-2">
                                            <h4><a href="#">{{ $review->customer->name }}</a></h4>
                                            <div class="ratings-container">
                                                <div class="ratings">
                                                    <div class="ratings-val" style="width: {{ $review->rating_percentage }}%;"></div>
                                                    <!-- End .ratings-val -->
                                                </div><!-- End .ratings -->
                                            </div><!-- End .rating-container -->
                                            <span class="review-date">{{ $review->created_at->diffForHumans() }}</span>
                                        </div><!-- End .col -->
                                        <div class="col-md-10">
                                            <div class="review-content">
                                                <p>{{ $review->review }}</p>
                                            </div><!-- End .review-content -->
                                        </div><!-- End .col-auto -->
                                    </div><!-- End .row -->
                                </div><!-- End .review -->
                            @empty
                                <div class="review">
                                    <div class="text-center">
                                        <p>Belum ada ulasan</p>
                                    </div>
                                </div>
                            @endforelse
                        </div><!-- End .reviews -->
                    </div><!-- .End .tab-pane -->
                </div><!-- End .tab-content -->
            </div><!-- End .product-details-tab -->

            <h2 class="title text-center mb-4">Produk Terkait</h2><!-- End .title text-center -->

            <div class="owl-carousel owl-simple carousel-equal-height carousel-with-shadow product-related"
                wire:ignore>
                <div class="product product-7 text-center">
                    <figure class="product-media">
                        <span class="product-label label-new">New</span>
                        <a href="product.html">
                            <img src="/build/assets/images/products/product-4.jpg" alt="Product image"
                                class="product-image">
                        </a>

                        <div class="product-action-vertical">
                            <a href="#" class="btn-product-icon btn-wishlist btn-expandable"><span>add to
                                    wishlist</span></a>
                            <a href="popup/quickView.html" class="btn-product-icon btn-quickview"
                                title="Quick view"><span>Quick
                                    view</span></a>
                            <a href="#" class="btn-product-icon btn-compare"
                                title="Compare"><span>Compare</span></a>
                        </div><!-- End .product-action-vertical -->

                        <div class="product-action">
                            <a href="#" class="btn-product btn-cart"><span>add to cart</span></a>
                        </div><!-- End .product-action -->
                    </figure><!-- End .product-media -->

                    <div class="product-body">
                        <div class="product-cat">
                            <a href="#">Women</a>
                        </div><!-- End .product-cat -->
                        <h3 class="product-title"><a href="product.html">Brown paperbag waist <br>pencil skirt</a>
                        </h3><!-- End .product-title -->
                        <div class="product-price">
                            $60.00
                        </div><!-- End .product-price -->
                        <div class="ratings-container">
                            <div class="ratings">
                                <div class="ratings-val" style="width: 20%;"></div><!-- End .ratings-val -->
                            </div><!-- End .ratings -->
                            <span class="ratings-text">( {{ $product->review_count }} Ulasan )</span>
                        </div><!-- End .rating-container -->

                        <div class="product-nav product-nav-thumbs">
                            <a href="#" class="active">
                                <img src="/build/assets/images/products/product-4-thumb.jpg" alt="product desc">
                            </a>
                            <a href="#">
                                <img src="/build/assets/images/products/product-4-2-thumb.jpg" alt="product desc">
                            </a>

                            <a href="#">
                                <img src="/build/assets/images/products/product-4-3-thumb.jpg" alt="product desc">
                            </a>
                        </div><!-- End .product-nav -->
                    </div><!-- End .product-body -->
                </div><!-- End .product -->

                <div class="product product-7 text-center">
                    <figure class="product-media">
                        <span class="product-label label-out">Out of Stock</span>
                        <a href="product.html">
                            <img src="/build/assets/images/products/product-6.jpg" alt="Product image"
                                class="product-image">
                        </a>

                        <div class="product-action-vertical">
                            <a href="#" class="btn-product-icon btn-wishlist btn-expandable"><span>add to
                                    wishlist</span></a>
                            <a href="popup/quickView.html" class="btn-product-icon btn-quickview"
                                title="Quick view"><span>Quick
                                    view</span></a>
                            <a href="#" class="btn-product-icon btn-compare"
                                title="Compare"><span>Compare</span></a>
                        </div><!-- End .product-action-vertical -->

                        <div class="product-action">
                            <a href="#" class="btn-product btn-cart"><span>add to cart</span></a>
                        </div><!-- End .product-action -->
                    </figure><!-- End .product-media -->

                    <div class="product-body">
                        <div class="product-cat">
                            <a href="#">Jackets</a>
                        </div><!-- End .product-cat -->
                        <h3 class="product-title"><a href="product.html">Khaki utility boiler jumpsuit</a></h3>
                        <!-- End .product-title -->
                        <div class="product-price">
                            <span class="out-price">$120.00</span>
                        </div><!-- End .product-price -->
                        <div class="ratings-container">
                            <div class="ratings">
                                <div class="ratings-val" style="width: 80%;"></div><!-- End .ratings-val -->
                            </div><!-- End .ratings -->
                            <span class="ratings-text">( 6 Reviews )</span>
                        </div><!-- End .rating-container -->
                    </div><!-- End .product-body -->
                </div><!-- End .product -->

                <div class="product product-7 text-center">
                    <figure class="product-media">
                        <span class="product-label label-top">Top</span>
                        <a href="product.html">
                            <img src="/build/assets/images/products/product-11.jpg" alt="Product image"
                                class="product-image">
                        </a>

                        <div class="product-action-vertical">
                            <a href="#" class="btn-product-icon btn-wishlist btn-expandable"><span>add to
                                    wishlist</span></a>
                            <a href="popup/quickView.html" class="btn-product-icon btn-quickview"
                                title="Quick view"><span>Quick
                                    view</span></a>
                            <a href="#" class="btn-product-icon btn-compare"
                                title="Compare"><span>Compare</span></a>
                        </div><!-- End .product-action-vertical -->

                        <div class="product-action">
                            <a href="#" class="btn-product btn-cart"><span>add to cart</span></a>
                        </div><!-- End .product-action -->
                    </figure><!-- End .product-media -->

                    <div class="product-body">
                        <div class="product-cat">
                            <a href="#">Shoes</a>
                        </div><!-- End .product-cat -->
                        <h3 class="product-title"><a href="product.html">Light brown studded Wide fit wedges</a></h3>
                        <!-- End .product-title -->
                        <div class="product-price">
                            $110.00
                        </div><!-- End .product-price -->
                        <div class="ratings-container">
                            <div class="ratings">
                                <div class="ratings-val" style="width: 80%;"></div><!-- End .ratings-val -->
                            </div><!-- End .ratings -->
                            <span class="ratings-text">( 1 Reviews )</span>
                        </div><!-- End .rating-container -->

                        <div class="product-nav product-nav-thumbs">
                            <a href="#" class="active">
                                <img src="/build/assets/images/products/product-11-thumb.jpg" alt="product desc">
                            </a>
                            <a href="#">
                                <img src="/build/assets/images/products/product-11-2-thumb.jpg" alt="product desc">
                            </a>

                            <a href="#">
                                <img src="/build/assets/images/products/product-11-3-thumb.jpg" alt="product desc">
                            </a>
                        </div><!-- End .product-nav -->
                    </div><!-- End .product-body -->
                </div><!-- End .product -->

                <div class="product product-7 text-center">
                    <figure class="product-media">
                        <a href="product.html">
                            <img src="/build/assets/images/products/product-10.jpg" alt="Product image"
                                class="product-image">
                        </a>

                        <div class="product-action-vertical">
                            <a href="#" class="btn-product-icon btn-wishlist btn-expandable"><span>add to
                                    wishlist</span></a>
                            <a href="popup/quickView.html" class="btn-product-icon btn-quickview"
                                title="Quick view"><span>Quick
                                    view</span></a>
                            <a href="#" class="btn-product-icon btn-compare"
                                title="Compare"><span>Compare</span></a>
                        </div><!-- End .product-action-vertical -->

                        <div class="product-action">
                            <a href="#" class="btn-product btn-cart"><span>add to cart</span></a>
                        </div><!-- End .product-action -->
                    </figure><!-- End .product-media -->

                    <div class="product-body">
                        <div class="product-cat">
                            <a href="#">Jumpers</a>
                        </div><!-- End .product-cat -->
                        <h3 class="product-title"><a href="product.html">Yellow button front tea top</a></h3>
                        <!-- End .product-title -->
                        <div class="product-price">
                            $56.00
                        </div><!-- End .product-price -->
                        <div class="ratings-container">
                            <div class="ratings">
                                <div class="ratings-val" style="width: 0%;"></div><!-- End .ratings-val -->
                            </div><!-- End .ratings -->
                            <span class="ratings-text">( 0 Reviews )</span>
                        </div><!-- End .rating-container -->
                    </div><!-- End .product-body -->
                </div><!-- End .product -->

                <div class="product product-7 text-center">
                    <figure class="product-media">
                        <a href="product.html">
                            <img src="/build/assets/images/products/product-7.jpg" alt="Product image"
                                class="product-image">
                        </a>

                        <div class="product-action-vertical">
                            <a href="#" class="btn-product-icon btn-wishlist btn-expandable"><span>add to
                                    wishlist</span></a>
                            <a href="popup/quickView.html" class="btn-product-icon btn-quickview"
                                title="Quick view"><span>Quick
                                    view</span></a>
                            <a href="#" class="btn-product-icon btn-compare"
                                title="Compare"><span>Compare</span></a>
                        </div><!-- End .product-action-vertical -->

                        <div class="product-action">
                            <a href="#" class="btn-product btn-cart"><span>add to cart</span></a>
                        </div><!-- End .product-action -->
                    </figure><!-- End .product-media -->

                    <div class="product-body">
                        <div class="product-cat">
                            <a href="#">Jeans</a>
                        </div><!-- End .product-cat -->
                        <h3 class="product-title"><a href="product.html">Blue utility pinafore denim dress</a></h3>
                        <!-- End .product-title -->
                        <div class="product-price">
                            $76.00
                        </div><!-- End .product-price -->
                        <div class="ratings-container">
                            <div class="ratings">
                                <div class="ratings-val" style="width: 20%;"></div><!-- End .ratings-val -->
                            </div><!-- End .ratings -->
                            <span class="ratings-text">( {{ $product->review_count }} Ulasan )</span>
                        </div><!-- End .rating-container -->
                    </div><!-- End .product-body -->
                </div><!-- End .product -->
            </div><!-- End .owl-carousel -->
        </div><!-- End .container -->
    </div><!-- End .page-content -->
</main>

@push('styles')
    <style>
        .radio-color:focus {
            cursor: pointer;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('build/assets/js/jquery.elevateZoom.min.js') }}"></script>
    <script src="{{ asset('build/assets/js/bootstrap-input-spinner.js') }}"></script>

    <script>
        $(".product-image-gallery").owlCarousel({
            nav: false,
            dots: false,
            margin: 7,
            loop: false,
            items: 4
        })

        $(".product-related").owlCarousel({
            nav: false,
            dots: true,
            margin: 20,
            loop: false,
            responsive: {
                0: {
                    items: 1
                },
                480: {
                    items: 2
                },
                768: {
                    items: 3
                },
                992: {
                    items: 4
                },
                1200: {
                    items: 4,
                    nav: true,
                    dots: false
                }
            }
        })

        Livewire.on('variantChanged', data => {
            if (data.image !== null) {
                $("#product-zoom-gallery").find(`a[data-image='${data.image}']`).click();
            }
        })
    </script>
@endpush
