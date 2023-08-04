<div>
    <main class="main">
        <nav aria-label="breadcrumb" class="breadcrumb-nav">
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item"><a href="#">Keranjang Belanja</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                </ol>
            </div><!-- End .container -->
        </nav><!-- End .breadcrumb-nav -->

        <div class="page-content">
            <div class="checkout">
                <div class="container">
                    <div class="checkout-discount">
                        <form wire:prevent.submit wire:ignore onsubmit="return false">
                            <input type="text" class="form-control" wire:model="voucher" onkeyup="this.value = this.value.toUpperCase();" id="checkout-discount-input" @if (!blank($selectedVoucher))
                                disabled
                            @endif>
                            <label for="checkout-discount-input" class="text-truncate">Punya Voucher Belanja? <span>Klik
                                    Disini</span></label>
                        </form>
                    </div><!-- End .checkout-discount -->
                    @if (!blank($voucher) && empty($selectedVoucher))
                        <a href="#" wire:click="applyVoucher">
                            <span wire:loading.class="d-none" wire:target="applyVoucher"><i class="icon-check"></i> Terapkan Voucher</span>
                            <div wire:loading wire:target="applyVoucher">
                                <i class="fa fa-spinner fa-spin"></i>
                            </div>
                        </a>
                    @endif

                    @if (!blank($voucher) && !empty($selectedVoucher))
                        <a href="#" wire:click="removeVoucher">
                            <span wire:loading.class="d-none" wire:target="removeVoucher"><i class="icon-close"></i> Hapus Voucher</span>
                            <div wire:loading wire:target="removeVoucher">
                                <i class="fa fa-spinner fa-spin"></i>
                            </div>
                        </a>

                    @endif
                    <form action="#">
                        <div class="row">
                            <div class="col-lg-6">
                                <h2 class="checkout-title">Informasi Pengiriman</h2><!-- End .checkout-title -->

                                <label>Alamat Pengiriman</label>
                                <p>
                                    <strong>{{ $shippingAddress->recipient_name }}</strong>
                                </p>
                                <p class="mb-1">
                                    {{ $shippingAddress->recipient_phone }}
                                </p>
                                <p class="mb-1">
                                    {{ $shippingAddress->full_address }}
                                </p>
                                <p>
                                    <a href="javascript:void(0);" wire:click="openAddressModal"
                                        class="border-bottom">Pilih Alamat Lain</a>
                                </p>
                                <p class="mt-2">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="is-dropship"
                                        wire:model="isDropship">
                                    <label class="custom-control-label" for="is-dropship">Kirim Sebagai Dropship</label>
                                </div>
                                </p>
                                @if ($isDropship)
                                    <div class="animate__animated animate__fadeIn">
                                        <h2 class="checkout-title">Informasi Dropshiper</h2>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <label for="">Nama Pengirim</label>
                                                <input type="text" class="form-control"
                                                    placeholder="Nama Dropshipper" wire:model="dropshipperName">
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="">Telp Pengirim</label>
                                                <input type="text" class="form-control" placeholder="No Hp/Telp"
                                                    wire:model="dropshipperPhone">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <hr>
                                <h2 class="checkout-title">Ekspedisi</h2>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <select class="form-control select-custom" wire:model="courierId">
                                            <option value="">Pilih Ekspedisi</option>
                                            @foreach ($couriers as $courier)
                                                <option value="{{ $courier->id }}">{{ $courier->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-6">
                                        <div wire:loading.class="d-block" class="d-none" wire:target="courierId">
                                            <span class="ssc-square" style="max-width: 277px; max-height: 40px;"></span>
                                        </div>
                                        <select class="form-control select-custom" wire:model="courierService"
                                            wire:loading.attr="hidden" wire:target="courierId"
                                            @if (!$courierId && empty($courierService)) disabled @endif>
                                            <option value="">
                                                Pilih Layanan
                                            </option>
                                            @foreach ($courierServices as $key => $item)
                                                <option value="{{ $item['value'] }}">
                                                    {{ $item['name'] . ' - ' . 'Rp. ' . number_format($item['cost'], 0, ',', '.') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div><!-- End .col-lg-9 -->
                            <aside class="col-lg-6">
                                <div class="summary">
                                    <h3 class="summary-title">Pesanan Kamu</h3><!-- End .summary-title -->

                                    <table class="table table-summary">
                                        <thead>
                                            <tr>
                                                <th>Produk ({{ $cart->item_count }} Item)</th>
                                                <th class="text-center">Jumlah</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($cart->items as $item)
                                                <tr>
                                                    <td>{{ $item->name }}</td>
                                                    <td class="text-center">
                                                        @if ($item->normal_price !== $item->price)
                                                            <span class="old-price">{{ $item->normal_price_label }}</span>
                                                        @endif
                                                        {{ "$item->price_label x$item->quantity" }}
                                                    </td>
                                                    <td>{{ $item->total_price_label }}</td>
                                                </tr>
                                            @endforeach
                                            <tr class="summary-subtotal">
                                                <td>Subtotal:</td>
                                                <td colspan="2">{{ $cart->subtotal_label }}</td>
                                            </tr><!-- End .summary-subtotal -->
                                            <tr>
                                                <td>Total Berat:</td>
                                                <td colspan="2">{{ $cart->total_weight . ' gram' }}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" class="text-left">
                                                    Biaya Kirim:
                                                    <span wire:loading.class="d-block" wire:target="courierService" class="ssc-line pull-right d-none" style="max-width: 70px;"></span>
                                                    <span class="pull-right" wire:loading.attr="hidden"
                                                        wire:target="courierService">{{ $shipping_cost_label }}</span>
                                                </td>
                                            </tr>
                                            @if (!empty($discountVoucher))
                                                <tr>
                                                    <td colspan="3" class="text-left">
                                                        Diskon Voucher:
                                                        <span class="pull-right old-price" wire:loading.class="d-none" wire:target="applyVoucher">
                                                            {{ format_rupiah($discountVoucher) }}
                                                        </span>
                                                        <span wire:loading.class="d-block" wire:target="applyVoucher" class="ssc-line pull-right d-none" style="max-width: 70px;"></span>
                                                    </td>
                                                </tr>
                                            @endif
                                            <tr class="summary-total">
                                                <td>Total:</td>
                                                <td colspan="2">
                                                    <span wire:loading.class="d-block" wire:target="courierService" class="ssc-line pull-right d-none" style="max-width: 90px;"></span>
                                                    <span wire:loading.attr="hidden"
                                                        wire:target="courierService">{{ $grandtotal_label }}</span>
                                                </td>
                                            </tr><!-- End .summary-total -->
                                        </tbody>
                                    </table><!-- End .table table-summary -->
                                    <button type="button" wire:click="submit"
                                        class="btn btn-outline-primary-2 btn-order btn-block"
                                        wire:loading.attr="disabled" wire:target="submit">
                                        <div wire:target="submit" wire:loading.class="d-none">
                                            <span class="btn-text">Buat Pesanan</span>
                                            <span class="btn-hover-text">Lanjut Pembayaran</span>
                                        </div>

                                        <div wire:loading wire:target="submit">
                                            <i class="fa fa-spinner fa-spin"></i>
                                        </div>
                                    </button>
                                </div><!-- End .summary -->
                            </aside><!-- End .col-lg-3 -->
                        </div><!-- End .row -->
                    </form>
                </div><!-- End .container -->
            </div><!-- End .checkout -->
        </div><!-- End .page-content -->
    </main><!-- End .main -->
</div>

<livewire:search-address selectedId="{{ $shippingAddress->id }}" />

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
@endpush
