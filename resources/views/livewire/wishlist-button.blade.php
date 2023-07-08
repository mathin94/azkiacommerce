<div>
    <div class="product-action-vertical">
        <a href="javascript:void(0);" class="btn-product-icon btn-expandable"
            title="Tambahkan {{ $product->name }} Ke Whistlist" wire:click="addToWishlist">
            <div >
                <span><i wire:loading wire:target="addToWishlist" class="fa fa-spinner fa-spin"></i></span>
            </div>

            @if ($liked == true)
                <i class="icon-heart" wire:loading.class="d-none"></i>
                <span wire:loading.class="d-none">Hapus dari wishlist</span></a>
            @else
                <i class="icon-heart-o" wire:loading.class="d-none"></i>
                <span wire:loading.class="d-none">tambahkan ke wishlist</span>
            @endif
        </a>
    </div>
</div>
