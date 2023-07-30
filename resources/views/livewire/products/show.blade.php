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

                            @if ($product->activeDiscount?->is_flash_sale)
                                <div class="product-countdown" data-until="{{ $product->activeDiscount?->inactive_at->format('Y, m, d, H, i') }}"></div>
                            @endif

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
                                    @foreach ($sizes as $size)
                                        <button wire:click="setSize({{ $size->id }})"
                                            class="btn btn-outline-dark selectable position-relative {{ $sizeId === $size->id ? 'active' : '' }}">
                                            <input type="radio">
                                            {{ $size->name }}
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
                                    <a href="javascript:void(0);" wire:click="orderWhatsapp" class="btn-product btn-message" id="btn-order-wa" title="Order via Whatsapp">
                                        <span wire:loading.class="d-none" wire:target="orderWhatsapp">
                                            <i class="icon-whatsapp"></i> Order via Whatsapp
                                        </span>

                                        <x-css-spinner-alt wire:loading class="ml-5 fa-spin" wire:target="orderWhatsapp" />
                                    </a>
                                </div><!-- End .details-action-wrapper -->
                            </div><!-- End .product-details-action -->

                            <div class="product-details-footer">
                                <div class="product-cat">
                                    <span>Kategori Produk:</span>
                                    <a href="{{ $product->category->public_url }}">{{ $product->category->name }}</a>
                                </div><!-- End .product-cat -->

                                <livewire:sharer-cta
                                    :title="'Beli' . $product->name"
                                    :url="$product->public_url"
                                    class='social-icons social-icons-sm'
                                    label='Bagikan'
                                />
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
                                                <h4>{{ $review->product_name }}</h4>
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

            <livewire:products.related :product="$product" />
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

        Livewire.on('openWhatsappLink', data => {
            window.open(data.url, '_blank');
        })
    </script>
@endpush
