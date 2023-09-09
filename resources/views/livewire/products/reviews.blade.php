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
    @if ($reviews->count() < $product->review_count)
    <div class="more-container text-center mb-0 mt-3">
        <button wire:click="loadMore" wire:loading.attr="disabled" class="btn btn-outline-darker btn-more">
            <div wire:loading wire:target="loadMore">
                <span>Sedang Memuat...</span><i class="fa fa-spinner fa-spin"></i>
            </div>
            <div wire:loading.class="d-none" wire:target="loadMore">
                <span>Lihat Lebih Banyak</span><i class="icon-long-arrow-down"></i>
            </div>
        </button>
    </div>
    @endif
</div><!-- End .reviews -->
