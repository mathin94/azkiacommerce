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
