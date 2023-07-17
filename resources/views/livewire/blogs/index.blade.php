 <main class="main">
     <nav aria-label="breadcrumb" class="breadcrumb-nav mb-2">
         <div class="container">
             <ol class="breadcrumb">
                 <li class="breadcrumb-item"><a href="/">Home</a></li>
                 <li class="breadcrumb-item"><a href="{{ route('blogs.index') }}">Blogs</a></li>
             </ol>
         </div><!-- End .container -->
     </nav><!-- End .breadcrumb-nav -->

     <div class="page-content">
         <div class="container">
             <nav class="blog-nav">
                 <ul class="menu-cat entry-filter justify-content-center">
                     <li class="{{ !$category ? 'active' : '' }}"><a href="{{ route('blogs.index') }}">Semua
                             Kategori</a></li>
                     @foreach ($categories as $item)
                         <li class="{{ $category == $item->slug ? 'active' : '' }}">
                             <a
                                 href="{{ route('blogs.index', ['category' => $item->slug]) }}">{{ $item->name }}<span>{{ $item->post_count }}</span></a>
                         </li>
                     @endforeach
                 </ul><!-- End .blog-menu -->
             </nav><!-- End .blog-nav -->

             <div class="entry-container" data-layout="fitRows">
                 @foreach ($posts as $item)
                     <div class="entry-item lifestyle shopping col-sm-6 col-lg-4">
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
                     </div><!-- End .entry-item -->
                 @endforeach
             </div><!-- End .entry-container -->

             <div class="mb-3"></div><!-- End .mb-3 -->

             <nav aria-label="Page navigation">
                 {{ $posts->links() }}
             </nav>
         </div><!-- End .container -->
     </div><!-- End .page-content -->
 </main><!-- End .main -->
