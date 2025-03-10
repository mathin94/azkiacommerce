<div>
    <div class="product product-1 text-center">
        <figure class="product-media">
            @if ($product->discount_percentage > 0)
            <span class="product-label label-sale">Diskon {{ $product->discount_percentage }}%</span>
            @endif
            <a href="{{ $product->public_url }}">
                <img src="{{ $product->main_image_url }}" alt="{{ $product->name }} image" class="product-image">
            </a>

            @if ($product->is_active_flash_sale)
                <div class="deal-countdown offer-countdown" data-until="{{ $product->discount_remaining_seconds_label }}"></div>
            @endif

            <livewire:wishlist-button :product=$product :key="'item-'.$product->id"/>

            {{-- <div class="product-action action-icon-top">
                <a href="#" class="btn-product btn-cart">
                    <span>Tambahkan Ke Keranjang</span></a>
            </div><!-- End .product-action --> --}}


        </figure>

        <div class="product-body">
            <div class="product-cat">
                <a href="{{ $product->category->public_url }}">{{ $product->category_name }}</a>
            </div><!-- End .product-cat -->
            <h3 class="product-title"><a href="{{ $product->public_url }}">{{ $product->name }}</a>
            </h3>
            <!-- End .product-title -->
            <div class="product-price">
                @if ($product->discount_percentage > 0)
                <span class="old-price">{{ $product->normal_price_label }}</span>
                @endif
                <span class="new-price">{{ $product->price_label }}</span>
            </div><!-- End .product-price -->
            <div class="ratings-container">
                <div class="ratings">
                    <div class="ratings-val" style="width: {{ $product->rating_percentage }}%;"></div><!-- End .ratings-val -->
                </div><!-- End .ratings -->
                <span class="ratings-text">( {{ $product->review_count }} Ulasan )</span>
            </div><!-- End .rating-container -->
        </div>
    </div>
</div>
