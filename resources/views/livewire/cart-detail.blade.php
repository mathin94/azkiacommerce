<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item"><a href="#">Shop</a></li>
                <li class="breadcrumb-item active" aria-current="page">Keranjang Belanja</li>
            </ol>
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->

    <div class="page-content">
        <div class="cart">
            <div class="container">
                <div class="row">
                    <div class="col-lg-9">
                        <table class="table table-cart table-mobile">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga Satuan</th>
                                    <th class="text-center">Jumlah</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                @if ($cartItems)
                                    @foreach ($cartItems as $item)
                                        <tr>
                                            <td class="product-col">
                                                <div class="product">
                                                    <figure class="product-media">
                                                        <a href="#">
                                                            <img src="{{ $item->product_image_url }}"
                                                                alt="{{ $item->name }}">
                                                        </a>
                                                    </figure>

                                                    <h3 class="product-title">
                                                        <a href="#">
                                                            {{ $item->alternate_name }} <br>
                                                            <small>{{ $item->name }}
                                                                ({{ "$item->weight gram" }})
                                                            </small>
                                                        </a>
                                                    </h3><!-- End .product-title -->
                                                </div><!-- End .product -->
                                            </td>
                                            <td class="price-col text-nowrap">
                                                @if ($item->discount > 0)
                                                    <span class="old-price">{{ $item->normal_price_label }}</span> <br>
                                                @endif
                                                <span class="new-price">{{ $item->price_label }}</span>
                                            </td>
                                            <td class="quantity-col" wire:ignore>
                                                <div class="cart-product-quantity" wire:ignore>
                                                    <input type="number" class="form-control" wire:ignore
                                                        value="{{ $itemQuantities[$item->id] }}" min="1"
                                                        step="1" data-decimals="0"
                                                        wire:model="itemQuantities.{{ $item->id }}">
                                                </div><!-- End .cart-product-quantity -->
                                            </td>
                                            <td class="total-col text-nowrap">
                                                {{ $item->total_price_label }}</td>
                                            <td class="remove-col">
                                                <button class="btn-remove" wire:click="deleteItem({{ $item->id }})">
                                                    <i class="icon-close"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div><!-- End .col-lg-9 -->
                    <aside class="col-lg-3">
                        <div class="summary summary-cart">
                            <h3 class="summary-title">Cart Total</h3><!-- End .summary-title -->

                            <table class="table table-summary">
                                <tbody>
                                    <tr class="summary-subtotal">
                                        <td>Total Item :</td>
                                        <td>{{ $cartItems?->sum('quantity') ?? 0 }}</td>
                                    </tr>
                                    <tr class="summary-subtotal">
                                        <td>Total Berat :</td>
                                        <td>{{ $cart->total_weight ?? 0 }} gram</td>
                                    </tr>
                                    <tr class="summary-total">
                                        <td>Total:</td>
                                        <td>{{ $cart->subtotal_label ?? 'Rp. 0' }}</td>
                                    </tr><!-- End .summary-total -->
                                </tbody>
                            </table><!-- End .table table-summary -->
                            <a href="{{ route('cart.checkout') }}"
                                class="btn btn-outline-primary-2 btn-order btn-block">LANJUT
                                KE PEMBAYARAN</a>
                        </div><!-- End .summary -->
                    </aside><!-- End .col-lg-3 -->
                </div><!-- End .row -->
            </div><!-- End .container -->
        </div><!-- End .cart -->
    </div><!-- End .page-content -->
</main>

@push('scripts')
    <script src="{{ asset('build/assets/js/bootstrap-input-spinner.js') }}"></script>
@endpush
