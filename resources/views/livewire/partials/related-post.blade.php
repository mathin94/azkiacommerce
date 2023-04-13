@if (!empty($posts))
    <div class="related-posts">
        <h3 class="title">Artikel Terkait</h3><!-- End .title -->

        <div class="owl-carousel owl-simple related-posts-slider">
            @foreach ($posts as $item)
                <article class="entry entry-grid">
                    <figure class="entry-media">
                        <a href="{{ $item->public_url }}">
                            <img src="{{ $item->image_url }}" alt="{{ $item->title }} image">
                        </a>
                    </figure><!-- End .entry-media -->

                    <div class="entry-body">
                        <div class="entry-meta">
                            <a href="#">{{ $item->published_at->format('d M, Y') }}</a>
                            <span class="meta-separator">|</span>
                            <a href="#">{{ $item->comments->count() }} Komentar</a>
                        </div><!-- End .entry-meta -->

                        <h2 class="entry-title">
                            <a href="{{ $item->public_url }}">{{ $item->title }}</a>
                        </h2><!-- End .entry-title -->

                        <div class="entry-cats">
                            <a href="#">{{ $item->category?->name }}</a>
                        </div><!-- End .entry-cats -->
                    </div><!-- End .entry-body -->
                </article>
            @endforeach
        </div><!-- End .owl-carousel -->
    </div><!-- End .related-posts -->

    @push('scripts')
        <script>
            $(".related-posts-slider").owlCarousel({
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
                    }
                }
            })
        </script>
    @endpush
@endif
