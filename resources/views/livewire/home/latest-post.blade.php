@if ($posts)
    <div class="blog-posts">
        <div class="container">
            <h2 class="title text-center">Blog Terbaru</h2><!-- End .title-lg text-center -->

            <div class="blog-article owl-carousel owl-simple carousel-with-shadow">
                @foreach ($posts as $item)
                    <article class="entry entry-mask">
                        <figure class="entry-media entry-video">
                            <a href="{{ $item->public_url }}">
                                <img src="{{ $item->image_url }}" alt="{{ $item->title }} desc">
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

            <div class="more-container text-center mt-2">
                <a href="{{ route('blogs.index') }}" class="btn btn-outline-darker btn-more"><span>Lihat Semua
                        Artikel</span><i class="icon-long-arrow-right"></i></a>
            </div><!-- End .more-container -->
        </div><!-- End .container -->
    </div><!-- End .blog-posts -->

    @push('scripts')
        <script>
            $('.blog-article').owlCarousel({
                nav: false,
                dots: true,
                margin: 20,
                loop: false,
                responsive: {
                    0: {
                        items: 1
                    },
                    600: {
                        items: 2
                    },
                    992: {
                        items: 3
                    }
                }
            })
        </script>
    @endpush
@endif
