@props([
    'product' => $product ?? null,
])

@if ($product)
    <div class="product product-2">
        <figure class="product-media">
            <a href="{{ $product->public_url }}">
                <img src="{{ $product->first_image_url }}" alt="Product image" class="product-image">
                <img src="{{ $product->second_image_url }}" alt="Product image" class="product-image-hover">
            </a>

            <div class="product-action-vertical">
                <a href="#" class="btn-product-icon btn-wishlist btn-expandable"
                    title="Tambahkan Ke Whistlist"><span>tambahkan ke wishlist</span></a>
            </div>

            <div class="product-action ">
                <a href="#" class="btn-product btn-cart"><span>tambahkan ke keranjang</span></a>
            </div>
        </figure>

        <div class="product-body">
            <div class="product-cat">
                <a href="#">{{ $product->category_name }}</a>
            </div>
            <h3 class="product-title"><a href="{{ $product->public_url }}">{{ $product->name }}</a></h3>
            <div class="product-price">
                {{ $product->price_label }}
            </div>
        </div>
    </div>
@endif
