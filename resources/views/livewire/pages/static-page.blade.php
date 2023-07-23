<div>
    <main class="main">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Informasi</a></li>
                    <li class="breadcrumb-item active" aria-current="page">{{ $page->title }}</li>
                </ol>
            </div>
        </nav>

        <div class="page-content">
            <div class="container">
                <h2 class="title text-center mb-3">{{ $page->title }}</h2><!-- End .title -->
                <div class="entry-content editor-content">
                    @if ($page->media)
                        @foreach ($page->media as $item)
                            <figure class="entry-media">
                                <img src="{{ $item->getUrl() }}" alt="{{ $page->title }}">
                            </figure>
                        @endforeach
                    @endif

                    {!! $page->content !!}
                </div>
            </div><!-- End .container -->
        </div><!-- End .page-content -->

        <livewire:contact-us-cta />
    </main>
</div>

@push('meta')
    <!-- Meta tags for SEO -->
    <meta name="description" content="{{ \Str::limit(strip_tags($page->content), 150) }}">
    {{-- <meta name="keywords" content="keyword1, keyword2, keyword3"> --}}

    <!-- Open Graph Meta Tags (for social sharing) -->
    <meta property="og:description" content="Add your Open Graph description here.">
    <meta property="og:image" content="{{ $page->thumbnail_url }}">
    <meta property="og:url" content="{{ $page->public_url }}">
    <meta property="og:type" content="website">

    <!-- Twitter Card Meta Tags (for Twitter sharing) -->
    {{-- <meta name="twitter:card" content="summary_large_image"> --}}
    <meta name="twitter:description" content="{{ \Str::limit(strip_tags($page->content), 150) }}">
    <meta name="twitter:image" content="{{ $page->thumbnail_url }}">
    {{-- <meta name="twitter:site" content="@YourTwitterHandle">
    <meta name="twitter:creator" content="@YourTwitterHandle"> --}}
@endpush
