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

        <div class="cta cta-display bg-image pt-4 pb-4"
            style="background-image: url(/build/assets/images/backgrounds/cta/bg-7.jpg);">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-10 col-lg-9 col-xl-7">
                        <div class="row no-gutters flex-column flex-sm-row align-items-sm-center">
                            <div class="col">
                                <h3 class="cta-title text-white">Kamu punya pertanyaan ?</h3><!-- End .cta-title -->
                                <p class="cta-desc text-white">Silahkan untuk menghubungi kami</p><!-- End .cta-desc -->
                            </div><!-- End .col -->

                            <div class="col-auto">
                                <a href="/p/kontak-kami" class="btn btn-outline-white"><span>HUBUNGI KAMI</span><i
                                        class="icon-long-arrow-right"></i></a>
                            </div><!-- End .col-auto -->
                        </div><!-- End .row no-gutters -->
                    </div><!-- End .col-md-10 col-lg-9 -->
                </div><!-- End .row -->
            </div><!-- End .container -->
        </div><!-- End .cta -->
    </main>
</div>
