<div class="entry-item lifestyle shopping col-sm-6 col-lg-4">
    <article class="entry entry-mask">
        <figure class="entry-media">
            <a href="{{ route('blogs.show', $item->slug) }}">
                <img src="{{ $item->image_url }}" alt="{{ $item->title }} image" style="height: 350px;">
            </a>
        </figure><!-- End .entry-media -->

        <div class="entry-body">
            <div class="entry-meta">
                <a href="#">{{ $item->published_at->format('d M, Y') }}</a>
                <span class="meta-separator">|</span>
                <a href="#">{{ $item->comments->count() }} Komentar</a>
            </div><!-- End .entry-meta -->

            <h2 class="entry-title">
                <a href="{{ route('blogs.show', $item->slug) }}">{{ $item->title }}.</a>
            </h2><!-- End .entry-title -->

            <div class="entry-cats">
                <a href="#">{{ $item->category?->name }}</a>,
            </div><!-- End .entry-cats -->
        </div><!-- End .entry-body -->
    </article><!-- End .entry -->
</div>
