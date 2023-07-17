<div>
    <div class="row">
        <div class="col-12">
            <div class="product-search">
                <livewire:search-product-variant :cart=$cart :customer=$customer />
            </div>
            <div class="cart">
                <table class="table table-cart table-mobile">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th class="text-center">Harga Satuan</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-right">Total</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        @if ($cartItems)
                            @foreach ($cartItems as $item)
                                <tr>
                                    <td class="product-col">
                                        <div class="product">
                                            <button class="btn-remove mr-3" title="Hapus produk" wire:click="deleteItem({{ $item->id }})">
                                                <i class="fa fa-trash text-danger"></i>
                                            </button>
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
                                    <td class="price-col text-nowrap text-center">
                                        @if ($item->normal_price != $item->price)
                                            <span class="old-price">{{ $item->normal_price_label }}</span> <br>
                                        @endif
                                        <span class="new-price">{{ $item->price_label }}</span>
                                    </td>
                                    <td class="quantity-col" wire:ignore>
                                        <div class="cart-product-quantity" wire:ignore>
                                            <input type="number" class="form-control bg-white" wire:ignore
                                                value="{{ $itemQuantities[$item->id] }}" min="1"
                                                step="1" data-decimals="0"
                                                wire:model="itemQuantities.{{ $item->id }}">
                                        </div><!-- End .cart-product-quantity -->
                                    </td>
                                    <td class="total-col text-nowrap text-right">
                                        {{ $item->total_price_label }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                    <tfoot>
                        <tr class="summary-subtotal">
                            <td class="product-col text-right mr-3" colspan="3">Total Item :</td>
                            <td class="price-col text-right">
                                <span class="new-price">{{ $cartItems?->sum('quantity') ?? 0 }} Pcs</span>
                            </td>
                        </tr>
                        <tr class="summary-subtotal">
                            <td class="product-col text-right mr-3" colspan="3">Total Berat :</td>
                            <td class="price-col text-right">
                                <span class="new-price">{{ $cart->total_weight ?? 0 }} gram</span>
                            </td>
                        </tr>
                        <tr class="">
                            <td class="product-col text-right mr-3" colspan="3">Total:</td>
                            <td class="price-col text-right">
                                <span class="new-price">{{ $cart->subtotal_label ?? 'Rp. 0' }}</span>
                            </td>
                        </tr><!-- End .summary-total -->
                    </tfoot>
                </table>

                <div class="text-right">
                    <a href="{{ route('cart.checkout') }}"
                                class="btn btn-success btn-order">LANJUT
                                KE PEMBAYARAN</a>
                </div>

            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('build/assets/js/bootstrap-input-spinner.js') }}"></script>
@endpush
