<div class="blog-posts pt-7 pb-7" style="background-color: #fafafa;">
    <div class="container">
       <h2 class="title-lg text-center mb-3 mb-md-4">Blog</h2><!-- End .title-lg text-center -->

        <div class="owl-carousel owl-simple carousel-with-shadow" data-toggle="owl"
            data-owl-options='{
                "nav": false,
                "dots": true,
                "items": 3,
                "margin": 20,
                "loop": false,
                "responsive": {
                    "0": {
                        "items":1
                    },
                    "600": {
                        "items":2
                    },
                    "992": {
                        "items":3
                    }
                }
            }'>
            @foreach ($posts as $item)
            <article class="entry entry-grid">
                <figure class="entry-media">
                    <a href="{{ $item->public_url }}">
                        <img src="{{ $item->image_url }}" alt="{{ $item->title }} desc">
                    </a>
                </figure><!-- End .entry-media -->

                <div class="entry-body text-center">
                    <div class="entry-meta">
                        <a href="#">{{ $item->published_at->format('d M, Y') }}</a>, {{ $item->comment_count_label }}
                    </div><!-- End .entry-meta -->

                    <h2 class="entry-title">
                        <a href="{{ $item->public_url }}">{{ $item->title }}</a>
                    </h2><!-- End .entry-title -->

                    <div class="entry-content">
                        <p>
                            {!! \Str::of($item->content)->limit(100) !!}
                        </p>
                        <a href="{{ $item->public_url }}" class="read-more">Baca Selengkapnya</a>
                    </div><!-- End .entry-content -->
                </div><!-- End .entry-body -->
            </article>
            @endforeach
        </div><!-- End .owl-carousel -->
    </div><!-- container -->

    <div class="more-container text-center mb-0 mt-3">
        <a href="{{ route('blogs.index') }}" class="btn btn-outline-darker btn-more"><span>Lihat Semua Artikel</span><i class="icon-long-arrow-right"></i></a>
    </div><!-- End .more-container -->
    <hr>
</div>

