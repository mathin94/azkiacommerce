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
                <form action="#">
                    <div class="row">
                        <div class="col-lg-6">
                            <h2 class="checkout-title">No Order : {{ $order->number }}</h2><!-- End .checkout-title -->

                            @if (empty($order->canceled_at))
                                <label>Mohon lakukan pembayaran sejumlah: </label>
                                <h5 class="mt-1 mb-1">
                                    <strong>{{ $order->grandtotal_label }}</strong>
                                </h5>
                                <p class="mb-2">
                                    Silahkan Transfer Ke Rekening Berikut:
                                </p>
                                @foreach ($bankAccounts as $bank)
                                    <div class="pl-3 mb-1" style="border-left: 8px solid #C0A230;">
                                        <b>{{ $bank->bank->name }}: </b><b>{{ $bank->account_number }}</b><br>
                                        <span style="font-size: 90%">a/n {{ $bank->account_name }}<br>
                                            {{ $bank->branch }}</span>
                                    </div>
                                @endforeach

                                <label class="font-weight-bold">PENTING:</label>
                                <div class="product-desc-content">
                                    <ul>
                                        <li>Mohon lakukan pembayaran dalam 1x24 jam</li>
                                        <li>Apabila sudah transfer dan status pembayaran belum berubah, mohon konfirmasi
                                            pembayaran manual di bawah</li>
                                        <li>Pesanan akan dibatalkan secara otomatis jika Anda tidak melakukan pembayaran.
                                        </li>
                                    </ul>
                                </div>
                            @else
                                <p>
                                    Pesanan Ini Sudah Dibatalkan
                                </p>
                            @endif

                            <livewire:upload-payment :order=$order />
                        </div><!-- End .col-lg-9 -->
                        <aside class="col-lg-6">
                            <div class="summary">
                                <h3 class="summary-title">Invoice #{{ $order->invoice_number }}</h3>
                                <!-- End .summary-title -->
                                <h3 class="summary-title">Daftar Pesanan</h3><!-- End .summary-title -->

                                <table class="table table-summary">
                                    <tbody>
                                        @foreach ($order->items as $item)
                                            <tr>
                                                <td class="pt-1 pb-1">
                                                    <strong style="font-size: 13px;">{{ $item->name }}</strong>
                                                    <p><small>Warna: {{ $item->color }}</small></p>
                                                    <p><small>Ukuran: {{ $item->size }}</small></p>
                                                </td>
                                                <td class="text-center">
                                                    <span class="cart-qty-show">{{ $item->quantity }}</span> x
                                                    {{ $item->price_label }} <p><small>Berat:
                                                            {{ $item->weight_label }}</small></p>
                                                </td>
                                                <td>{{ $item->total_price_label }}</td>
                                            </tr>
                                        @endforeach
                                        <tr class="summary-subtotal">
                                            <td>Subtotal:</td>
                                            <td colspan="2">{{ $order->subtotal_label }}</td>
                                        </tr><!-- End .summary-subtotal -->
                                        <tr>
                                            <td>Total Berat:</td>
                                            <td colspan="2">{{ $order->total_weight_label }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-left" colspan="3">Ekspedisi:
                                                <span class="pull-right">
                                                    {{ $order->shipping->courier_label }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Biaya Pengiriman ({{ $order->total_weight }}gr) :</td>
                                            <td colspan="2">{{ $order->shipping->shipping_cost_label }}</td>
                                        </tr>
                                        <tr class="summary-total">
                                            <td>Total:</td>
                                            <td colspan="2">{{ $order->grandtotal_label }}</td>
                                        </tr><!-- End .summary-total -->
                                    </tbody>
                                </table><!-- End .table table-summary -->
                                <h3 class="summary-title mt-2">Informasi Pengiriman</h3>
                                <table class=" table-summary">
                                    <tbody>
                                        @if ($order->shipping->is_dropship)
                                            <tr>
                                                <td width="5" class="text-nowrap pr-2">Nama Pengirim
                                                </td>
                                                <td width="1">:</td>
                                                <td class="text-left pl-2">{{ $order->shipping->dropshipper_name }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td width="5" class="text-nowrap pr-2">Telp Pengirim
                                                </td>
                                                <td width="1">:</td>
                                                <td class="text-left pl-2">{{ $order->shipping->dropshipper_phone }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="3">&nbsp;</td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td width="5" class="text-nowrap pr-2">Nama Penerima</td>
                                            <td width="1">:</td>
                                            <td class="text-left pl-2">{{ $order->shipping->recipient_name }}</td>
                                        </tr>
                                        <tr>
                                            <td width="5" class="text-nowrap pr-2">No. Hp</td>
                                            <td width="1">:</td>
                                            <td class="text-left pl-2">{{ $order->shipping->recipient_phone }}</td>
                                        </tr>
                                        <tr>
                                            <td width="5" class="text-nowrap align-top pr-2">Alamat</td>
                                            <td width="1" class="align-top">:</td>
                                            <td class="text-justify pl-2 align-top">
                                                {{ $order->shipping->full_address }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div><!-- End .summary -->
                        </aside><!-- End .col-lg-3 -->
                    </div><!-- End .row -->
                </form>
            </div><!-- End .container -->
        </div><!-- End .checkout -->
    </div><!-- End .page-content -->
</main><!-- End .main -->
