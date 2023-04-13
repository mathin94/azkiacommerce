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
                         <article class="entry entry-mask">
                             <figure class="entry-media">
                                 <a href="{{ route('blogs.show', $item->slug) }}">
                                     <img src="{{ $item->image_url }}" alt="{{ $item->title }} image"
                                         style="height: 350px;">
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
                                     <a
                                         href="{{ route('blogs.index', ['category' => $item->category?->slug]) }}">{{ $item->category?->name }}</a>,
                                 </div><!-- End .entry-cats -->
                             </div><!-- End .entry-body -->
                         </article><!-- End .entry -->
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
