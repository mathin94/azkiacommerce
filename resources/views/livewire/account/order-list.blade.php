<div>
    <nav class="blog-nav">
        <ul class="menu-cat entry-filter justify-content-center">
            <li class="{{ empty($tab) ? 'active' : '' }}"><a href="#">Semua Pesanan</a></li>
            <li class=""><a href="#">Sedang Berlangsung</a></li>
            <li class=""><a href="#">Selesai</a></li>
            <li class=""><a href="#">Dibatalkan</a></li>
        </ul><!-- End .blog-menu -->
    </nav>
    <div class="row">
        <div class="col-12">
            @foreach ($orders as $order)
                @php
                    $item = $order->items->first();
                @endphp
                <table class="table table-cart table-mobile">
                    <tbody>

                        <tr>
                            <td colspan="2" style="padding: 0;">
                                <x-heroicon-o-shopping-bag style="max-width: 15px;" /> Tanggal Pesanan :
                                {{ $order->date_format_id }}
                            </td>
                            <td>
                                <a href="#" wire:click="show({{ $order->id }})">
                                    <x-heroicon-o-hashtag style="max-width: 15px;" />
                                    {{ $order->number }}
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td class="product-col mr-4" width="150">
                                <div class="product">
                                    <figure class="product-media" style="max-width: 150px;">
                                        <a href="#">
                                            <img src="{{ $item->product_image_url }}" alt="{{ $item->name }}">
                                        </a>
                                    </figure>
                                </div><!-- End .product -->
                            </td>
                            <td class="align-top" width="290">
                                <h3 class="product-title ml-4">
                                    <a href="#">
                                        {{ $item->alternate_name }} <br>
                                        <small>{{ $item->name }}</small>
                                    </a>
                                    <p class="mt-1">
                                        Berat : {{ "$item->weight gram" }}
                                    </p>
                                    <p>
                                        {{ $item->price_label }} x {{ $item->quantity }}
                                    </p>
                                    @if ($order->items->count() > 1)
                                        <p class="mt-2">
                                            <a href="#"
                                                wire:click="show({{ $order->id }})">+{{ $order->items->count() - 1 }}
                                                Produk Lainnya</a>
                                        </p>
                                    @endif
                                </h3><!-- End .product-title -->
                            </td>
                            <td class="price-col text-nowrap align-top">
                                <h3 class="product-title">Total Pembayaran</h3>
                                <h3 class="product-title">{{ $item->total_price_label }}</h3>
                                <p class="mt-2">
                                    <span
                                        class="badge badge-{{ $order->status_color }}">{{ $order->status->description }}</span>
                                </p>
                            </td>

                        </tr>
                        @if ($order->status->value === App\Enums\OrderStatus::WaitingPayment)
                            <tr>
                                <td class="price-col align-top" colspan="2">
                                    Segera lakukan pembayaran dalam 1 x 24 jam, atau pesanan Anda akan Otomatis
                                    Dibatalkan.
                                </td>
                                <td>
                                    <button class="btn btn-primary">Upload Bukti Pembayaran</button>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            @endforeach
        </div>
    </div>

    @if ($detail)
        <div class="modal fade" id="order-modal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document" style="max-width: 800px;">
                <div class="modal-content" role="document">
                    <div class="modal-header p-4">
                        <h5 class="modal-title">
                            Detail Transaksi
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="icon-close"></i></span>
                        </button>
                    </div>
                    <div class="modal-body p-5">
                        <div class="row">
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        No. Invoice
                                    </div>
                                    <div class="col-md-8 text-right">
                                        <a href="#">{{ $detail->number }}</a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        Tanggal Pembelian
                                    </div>
                                    <div class="col-md-8 text-right">
                                        {{ $detail->date_format_id }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        Status Transaksi
                                    </div>
                                    <div class="col-md-8 text-right">
                                        <span
                                            class="badge badge-{{ $detail->status_color }}">{{ $detail->status->description }}</span>
                                    </div>
                                </div>
                                <hr>
                                <h3 class="product-title">Detail Produk</h3>
                                <div class="product-container mt-2">
                                    @foreach ($detail->items as $item)
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="product">
                                                    <figure class="product-media" style="max-width: 100px;">
                                                        <a href="#">
                                                            <img src="{{ $item->product_image_url }}"
                                                                alt="{{ $item->name }}">
                                                        </a>
                                                    </figure>
                                                </div><!-- End .product -->
                                            </div>
                                            <div class="col-md-6">
                                                <a href="#" style="color: black;">
                                                    {{ $item->alternate_name }} <br>
                                                    <small>{{ $item->name }}</small>
                                                </a>
                                                <p>
                                                    {{ $item->quantity }} x {{ $item->price_label }}
                                                </p>
                                            </div>
                                            <div class="col-md-4 text-right">
                                                <span>Total Harga</span>
                                                <p>
                                                    {{ $item->total_price_label }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <hr>
                                <h3 class="product-title">Info Pengiriman</h3>
                                <div class="shipping mt-2">
                                    <div class="row">
                                        <div class="col-12">
                                            <table class="">
                                                <tr>
                                                    <td width="80">Kurir</td>
                                                    <td width="1">:</td>
                                                    <td class="text-left">
                                                        <span
                                                            class="pl-3">{{ $detail->shipping->courier_label_alternative }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="80">No. Resi</td>
                                                    <td width="1">:</td>
                                                    <td class="text-left">
                                                        <span
                                                            class="pl-3">{{ $detail->shipping->receipt_number ?? 'Belum ada resi' }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="80" class="align-top">Alamat</td>
                                                    <td width="1" class="align-top">:</td>
                                                    <td class="text-left align-top pl-3">
                                                        <strong>{{ $detail->shipping->recipient_name }}</strong>
                                                        <p>{{ $detail->shipping->recipient_phone }}</p>
                                                        <p>
                                                            {{ $detail->shipping->full_address }}
                                                        </p>
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <h3 class="product-title">Rincian Pembayaran</h3>
                                <div class="payment mt-2">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    Metode Pembayaran
                                                </div>
                                                <div class="col-md-8 text-right">
                                                    Transfer Bank
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    Total Harga ({{ $detail->total_item }} Barang)
                                                </div>
                                                <div class="col-md-8 text-right">
                                                    {{ $detail->subtotal_label }}
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    Total Ongkos Kirim ({{ $detail->total_weight }} gr)
                                                </div>
                                                <div class="col-md-8 text-right">
                                                    {{ $detail->shipping_cost_label }}
                                                </div>
                                            </div>
                                            <hr class="mt-1 mb-1">
                                            <div class="row font-weight-bold">
                                                <div class="col-md-4">
                                                    Total Belanja
                                                </div>
                                                <div class="col-md-8 text-right">
                                                    {{ $detail->final_price_label }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div><!-- End .modal-body -->
                    </div><!-- End .modal-content -->
                </div><!-- End .modal-dialog -->
            </div><!-- End .modal -->
    @endif
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Livewire.on('open-modal', function() {
                $('#order-modal').modal('show');
            })
        })
    </script>
@endpush
