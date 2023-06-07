<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav mb-2">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produk</a></li>
            </ol>
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->

    <div class="page-content">
        <div class="container">
            <div class="toolbox">
                <div class="toolbox-left">
                    <a href="#" class="sidebar-toggler"><i class="icon-bars"></i>Filter Data</a>
                </div><!-- End .toolbox-left -->

                <div class="toolbox-right">
                    <div class="toolbox-sort">
                        <label for="sortby">Urutkan:</label>
                        <div class="select-custom">
                            <select name="sortby" id="sortby" class="form-control">
                                <option>Terbaru</option>
                                <option>Paling Populer</option>
                                <option>Nama (A-Z)</option>
                                <option>Nama (Z-A)</option>
                                <option>Harga Terendah</option>
                                <option>Harga Tertinggi</option>
                            </select>
                        </div>
                    </div><!-- End .toolbox-sort -->
                </div><!-- End .toolbox-right -->
            </div><!-- End .toolbox -->

            <div class="products">
                <div class="row">
                    @foreach ($products as $item)
                        <div class="col-6 col-md-4 col-lg-4 col-xl-3">
                            <div class="product">
                                <figure class="product-media">
                                    <span class="product-label label-new">New</span>
                                    <a href="{{ $item->public_url }}">
                                        <img src="{{ $item->main_image_url }}" alt="{{ $item->name }} image"
                                            class="product-image">
                                    </a>

                                    <div class="product-action-vertical">
                                        <a href="#" class="btn-product-icon btn-wishlist btn-expandable">
                                            <span>tambahkan ke wishlist</span></a>
                                    </div><!-- End .product-action -->

                                    {{-- <div class="product-action action-icon-top">
                                        <a href="#" class="btn-product btn-cart">
                                            <span>Tambahkan Ke Keranjang</span></a>
                                    </div><!-- End .product-action --> --}}
                                </figure><!-- End .product-media -->

                                <div class="product-body">
                                    <div class="product-cat">
                                        <a href="{{ $item->category->public_url }}">{{ $item->category_name }}</a>
                                    </div><!-- End .product-cat -->
                                    <h3 class="product-title"><a href="{{ $item->public_url }}">{{ $item->name }}</a>
                                    </h3>
                                    <!-- End .product-title -->
                                    <div class="product-price">
                                        {{ $item->price_label }}
                                    </div><!-- End .product-price -->
                                    <div class="ratings-container">
                                        <div class="ratings">
                                            <div class="ratings-val" style="width: 0%;"></div><!-- End .ratings-val -->
                                        </div><!-- End .ratings -->
                                        <span class="ratings-text">( 0 Reviews )</span>
                                    </div><!-- End .rating-container -->
                                </div><!-- End .product-body -->
                            </div><!-- End .product -->
                        </div><!-- End .col-sm-6 col-lg-4 col-xl-3 -->
                    @endforeach
                </div><!-- End .row -->

                <div class="load-more-container text-center">
                    {!! $products->links() !!}
                </div><!-- End .load-more-container -->
            </div><!-- End .products -->

            <div class="sidebar-filter-overlay"></div><!-- End .sidebar-filter-overlay -->
            <aside class="sidebar-shop sidebar-filter">
                <div class="sidebar-filter-wrapper">
                    <div class="widget widget-clean">
                        <label><i class="icon-close"></i>Filter</label>
                        <a href="#" class="sidebar-filter-clear">Bersihkan Filter</a>
                    </div><!-- End .widget -->
                    <div class="widget widget-collapsible">
                        <h3 class="widget-title">
                            <a data-toggle="collapse" href="#widget-1" role="button" aria-expanded="true"
                                aria-controls="widget-1">
                                Kategori
                            </a>
                        </h3><!-- End .widget-title -->

                        <div class="collapse show" id="widget-1">
                            <div class="widget-body">
                                <div class="filter-items filter-items-count">
                                    @foreach ($categories as $item)
                                        <div class="filter-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                    id="{{ $item->slug }}">
                                                <label class="custom-control-label"
                                                    for="{{ $item->slug }}">{{ $item->name }}</label>
                                            </div>
                                            <span class="item-count">{{ $item->product_count }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="widget widget-collapsible">
                        <h3 class="widget-title">
                            <a data-toggle="collapse" href="#widget-2" role="button" aria-expanded="true"
                                aria-controls="widget-2">
                                Ukuran
                            </a>
                        </h3>

                        <div class="collapse show" id="widget-2">
                            <div class="widget-body">
                                <div class="filter-items">
                                    @foreach ($sizes as $item)
                                        <div class="filter-item">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                    id="{{ $item->code }}">
                                                <label class="custom-control-label"
                                                    for="{{ $item->code }}">{{ Str::upper($item->name) }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="widget widget-collapsible">
                        <h3 class="widget-title">
                            <a data-toggle="collapse" href="#widget-3" role="button" aria-expanded="true"
                                aria-controls="widget-3">
                                Warna
                            </a>
                        </h3><!-- End .widget-title -->

                        <div class="collapse show" id="widget-3">
                            <div class="widget-body">
                                <div class="select-custom">
                                    <select name="color_id" id="color_id" class="form-control">
                                        <option value="">Semua Warna</option>
                                        @foreach ($colors as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div><!-- End .select-custom -->
                            </div><!-- End .widget-body -->
                        </div><!-- End .collapse -->
                    </div><!-- End .widget -->

                    <div class="widget widget-collapsible">
                        <h3 class="widget-title">
                            <a data-toggle="collapse" href="#widget-5" role="button" aria-expanded="true"
                                aria-controls="widget-5">
                                Harga
                            </a>
                        </h3><!-- End .widget-title -->

                        <div class="collapse show" id="widget-5">
                            <div class="widget-body">
                                <div class="filter-price">
                                    <div class="form-group">
                                        <label for="min-price">Harga Minimum</label>
                                        <input type="text" class="form-control" id="min-price">
                                    </div>
                                    <div class="form-group">
                                        <label for="max-price">Harga Maksimum</label>
                                        <input type="text" class="form-control" id="max-price">
                                    </div>
                                </div><!-- End .filter-price -->
                            </div><!-- End .widget-body -->
                        </div><!-- End .collapse -->
                    </div><!-- End .widget -->
                </div><!-- End .sidebar-filter-wrapper -->
            </aside><!-- End .sidebar-filter -->
        </div><!-- End .container -->
    </div><!-- End .page-content -->
</main>
