<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav mb-3">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Beranda</a></li>
                <li class="breadcrumb-item"><a href="{{ route('blogs.index') }}">Blog</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $post->title }}</li>
            </ol>
        </div>
    </nav>

    <div class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <article class="entry single-entry">
                        @if ($post->image_url)
                            <figure class="entry-media">
                                <img src="{{ $post->image_url }}" alt="{{ $post->title }} Image">
                            </figure>
                        @endif

                        <div class="entry-body">
                            <div class="entry-meta">
                                <span class="entry-author">
                                    Penulis : <a href="#">{{ $post->author->name }}</a>
                                </span>
                                <span class="meta-separator">|</span>
                                <a href="#">{{ $post->published_at->format('d M, Y') }}</a>
                                <span class="meta-separator">|</span>
                                <a href="#">{{ $post->comments->count() }} Komentar</a>
                            </div><!-- End .entry-meta -->

                            <h2 class="entry-title">
                                {{ $post->title }}
                            </h2><!-- End .entry-title -->

                            <div class="entry-cats">
                                <a href="#">{{ $post->category->name }}</a>
                            </div><!-- End .entry-cats -->

                            <div class="entry-content editor-content">
                                {!! $post->content !!}
                            </div><!-- End .entry-footer row no-gutters -->

                            <div class="entry-footer row no-gutters flex-column flex-md-row">
                                <div class="col-md">
                                    <div class="entry-tags">
                                        <span>Tags:</span>
                                        @foreach ($post->tags as $item)
                                            <a
                                                href="{{ route('blogs.index', ['tags' => $item->name]) }}">{{ $item->name }}</a>
                                        @endforeach
                                    </div><!-- End .entry-tags -->
                                </div><!-- End .col -->

                                <div class="social-icons social-icons-sm">
                                    <span class="social-label">Bagikan:</span>
                                    <a href="#" data-sharer="facebook"
                                        data-title="Beli {{ $post->title }} di {{ config('app.name') }}"
                                        data-url="{{ $post->public_url }}" class="social-icon"
                                        title="Facebook"><x-bi-facebook /></a>
                                    <a href="#" data-sharer="twitter"
                                        data-title="Beli {{ $post->title }} di {{ config('app.name') }}"
                                        data-url="{{ $post->public_url }}" class="social-icon" title="Twitter"><x-bi-twitter /></a>
                                    <a href="#" data-sharer="instagram"
                                        data-title="Beli {{ $post->title }} di {{ config('app.name') }}"
                                        data-url="{{ $post->public_url }}" class="social-icon"
                                        title="Instagram"><x-bi-instagram /></a>
                                    <a href="#" data-sharer="pinterest"
                                        data-title="Beli {{ $post->title }} di {{ config('app.name') }}"
                                        data-url="{{ $post->public_url }}" class="social-icon"
                                        title="Pinterest"><x-bi-pinterest /></a>
                                </div>
                            </div>
                        </div><!-- End .entry-body -->
                    </article><!-- End .entry -->

                    <nav class="pager-nav" aria-label="Page navigation">
                        <a class="pager-link pager-link-prev" href="{{ $prev_post->public_url ?? '#' }}"
                            aria-label="Previous" tabindex="-1">
                            Artikel Sebelumnya
                            <span class="pager-link-title">{{ $prev_post?->title }}</span>
                        </a>

                        @if ($next_post)
                            <a class="pager-link pager-link-next" href="{{ $next_post->public_url }}" aria-label="Next"
                                tabindex="-1">
                                Artikel Selanjutnya
                                <span class="pager-link-title">{{ $next_post->title }}</span>
                            </a>
                        @endif
                    </nav><!-- End .pager-nav -->

                    <livewire:partials.related-post :post=$post />

                    <div class="comments">
                        <h3 class="title">{{ $post->comments->count() }} Komentar</h3><!-- End .title -->
                        <ul>
                            @foreach ($post->comments as $item)
                                <li>
                                    <div class="comment">
                                        <figure class="comment-media">
                                            <a href="#">
                                                <img src="/build/assets/images/no-avatar.jpg" alt="{{ $item->customer->name }}">
                                            </a>
                                        </figure>

                                        <div class="comment-body">
                                            <div class="comment-user">
                                                <h4><a href="#">{{ $item->customer->name }}</a></h4>
                                                <span class="comment-date">{{ $item->created_at->format('d F Y H:i') }}</span>
                                            </div><!-- End .comment-user -->

                                            <div class="comment-content">
                                                <p>{{ $item->comment }}</p>
                                            </div><!-- End .comment-content -->
                                        </div><!-- End .comment-body -->
                                    </div><!-- End .comment -->
                                </li>
                            @endforeach
                        </ul>
                    </div><!-- End .comments -->
                    @if (auth()->guard('shop')->check())
                    <div class="reply">
                        <div class="heading">
                            <h3 class="title">Tuliskan Komentar</h3><!-- End .title -->
                            <p class="title-desc">Nama yang muncul akan sesuai dengan nama user login anda.</p>
                        </div><!-- End .heading -->

                        <form action="#" wire:submit.prevent="postComment">
                            <label for="reply-message" class="sr-only">Komentar</label>
                            <textarea name="reply-message"
                                wire:model.lazy="newComment"
                                wire:loading.attr="disabled"
                                id="reply-message"
                                cols="30" rows="4"
                                class="form-control" required
                                placeholder="Komentar *">
                            </textarea>

                            <button wire:click="postComment" wire:loading.attr="disabled" class="btn btn-outline-primary-2">
                                <div wire:loading.class="d-none" wire:target="postComment">
                                    <span>POSTING KOMENTAR</span>
                                    <i class="icon-long-arrow-right"></i>
                                </div>
                                <div wire:loading wire:target="postComment">
                                    <i class="fa fa-spinner fa-spin"></i>
                                </div>
                            </button>
                        </form>
                    </div><!-- End .reply -->
                    @else
                    <div class="reply">
                        <div class="heading">
                            <p class="title-desc">Anda harus login untuk menuliskan komentar.</p>
                        </div><!-- End .heading -->
                    </div><!-- End .reply -->
                    @endif
                </div><!-- End .col-lg-9 -->

                <aside class="col-lg-3">
                    <div class="sidebar">
                        <div class="widget widget-search">
                            <h3 class="widget-title">Pencarian</h3><!-- End .widget-title -->

                            <form action="#">
                                <label for="ws" class="sr-only">Cari artikel blog</label>
                                <input type="search" class="form-control" name="ws" id="ws"
                                    placeholder="Cari artikel blog" required>
                                <button type="submit" class="btn"><i class="icon-search"></i><span
                                        class="sr-only">Search</span></button>
                            </form>
                        </div><!-- End .widget -->

                        <div class="widget widget-cats">
                            <h3 class="widget-title">Kategori</h3><!-- End .widget-title -->

                            <ul>
                                @foreach ($categories as $item)
                                    <li>
                                        <a
                                            href="{{ route('blogs.index', ['category' => $item->slug]) }}">{{ $item->name }}<span>{{ $item->post_count }}</span></a>
                                    </li>
                                @endforeach
                            </ul>
                        </div><!-- End .widget -->

                        <livewire:partials.popular-post />
                    </div><!-- End .sidebar sidebar-shop -->
                </aside><!-- End .col-lg-3 -->
            </div><!-- End .row -->
        </div><!-- End .container -->
    </div>
</main>

@push('meta')
    <!-- Meta tags for SEO -->
    <meta name="description" content="{{ \Str::limit(strip_tags($post->content), 150) }}">
    {{-- <meta name="keywords" content="keyword1, keyword2, keyword3"> --}}

    <!-- Open Graph Meta Tags (for social sharing) -->
    <meta property="og:description" content="Add your Open Graph description here.">
    <meta property="og:image" content="{{ $post->image_url }}">
    <meta property="og:url" content="{{ $post->public_url }}">
    <meta property="og:type" content="website">

    <!-- Twitter Card Meta Tags (for Twitter sharing) -->
    {{-- <meta name="twitter:card" content="summary_large_image"> --}}
    <meta name="twitter:description" content="{{ \Str::limit(strip_tags($post->content), 150) }}">
    <meta name="twitter:image" content="{{ $post->image_url }}">
    {{-- <meta name="twitter:site" content="@YourTwitterHandle">
    <meta name="twitter:creator" content="@YourTwitterHandle"> --}}
@endpush
