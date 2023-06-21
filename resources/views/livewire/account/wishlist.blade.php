<div>
    @if (empty($wishlists))
        <div class="col-12">
            <div class="text-center">
                <x-lucide-package-open class="mt-5 mb-2" style="max-width: 150px;" />
                <p>
                <h5>Belum ada wishlist</h5>
                </p>
            </div>
        </div>
    @else
        <div class="row">
            <div class="page-content">
                <div class="container">
                    <div class="products">
                        <div class="row">
                            @forelse ($wishlists as $item)
                                <div class="col-6 col-md-4 col-lg-4 col-xl-3">
                                    <div class="product">
                                        <figure class="product-media">
                                            <a href="{{ $item->product->public_url }}">
                                                <img src="{{ $item->product->main_image_url }}"
                                                    alt="{{ $item->product->name }} image" class="product-image">
                                            </a>

                                            <div class="product-action-vertical">
                                                <a href="javascript:void(0);" wire:click="delete({{ $item->id }})"
                                                    class="btn-product-icon btn-expandable">
                                                    <i class="icon-heart"></i>
                                                    <span>Hapus dari wishlist</span></a>
                                            </div><!-- End .product-action -->
                                        </figure><!-- End .product-media -->

                                        <div class="product-body">
                                            <div class="product-cat">
                                                <a
                                                    href="{{ $item->product->category->public_url }}">{{ $item->product->category_name }}</a>
                                            </div><!-- End .product-cat -->
                                            <h3 class="product-title"><a
                                                    href="{{ $item->product->public_url }}">{{ $item->product->name }}</a>
                                            </h3>
                                            <!-- End .product-title -->
                                            <div class="product-price">
                                                {{ $item->product->price_label }}
                                            </div><!-- End .product-price -->
                                            <div class="ratings-container">
                                                <div class="ratings">
                                                    <div class="ratings-val" style="width: 0%;"></div>
                                                    <!-- End .ratings-val -->
                                                </div><!-- End .ratings -->
                                                <span class="ratings-text">( 0 Reviews )</span>
                                            </div><!-- End .rating-container -->
                                        </div><!-- End .product-body -->
                                    </div><!-- End .product -->
                                </div><!-- End .col-sm-6 col-lg-4 col-xl-3 -->
                            @endforeach
                        </div><!-- End .row -->

                        <div class="load-more-container text-center">
                            {!! $wishlists->links() !!}
                        </div><!-- End .load-more-container -->
                    </div><!-- End .products -->
                </div><!-- End .container -->
            </div>
        </div>
    @endif
</div>
