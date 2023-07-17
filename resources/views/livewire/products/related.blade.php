<div>
    @if ($relatedProducts->count() > 0)
    <h2 class="title text-center mb-4">Produk Terkait</h2><!-- End .title text-center -->

    <div class="owl-carousel owl-simple carousel-equal-height carousel-with-shadow product-related" wire:ignore>

        @foreach ($relatedProducts as $product)
        <livewire:product-figure :product="$product" />
        @endforeach
    </div>
    @endif
</div>
