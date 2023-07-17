<div>
    <div class="container recent-arrivals">
        <div class="heading heading-flex align-items-center mb-3">
            <h3 class="title text-center mb-3">Produk Terbaru</h3>
        </div><!-- End .heading -->

        <div class="content">
            <div class="tab-pane p-0 fade show active" id="recent-all-tab" role="tabpanel"
                aria-labelledby="recent-all-link">
                <div class="products">
                    <div class="row justify-content-center">
                        @foreach ($products as $item)
                        <div class="col-6 col-md-4 col-lg-3">
                            <livewire:product-figure :product=$item />
                        </div><!-- End .col-sm-6 col-md-4 col-lg-3 -->
                        @endforeach
                    </div><!-- End .row -->
                </div><!-- End .products -->
            </div><!-- .End .tab-pane -->
        </div><!-- End .tab-content -->

        <div class="more-container text-center mt-3 mb-3">
            <a href="{{ route('products.index') }}" class="btn btn-outline-dark-3 btn-more"><span>Tampilkan Selengkapnya</span><i
                    class="icon-long-arrow-right"></i></a>
        </div><!-- End .more-container -->
    </div><!-- End .container -->
</div>
