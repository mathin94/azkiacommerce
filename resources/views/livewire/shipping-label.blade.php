<body class="container">
    <table class="instruction no-print default">
        <tr>
            <td class="desc">
                <p><b>Catatan:</b></p>
                <p>Pilih kertas <em>A4</em> dan atur layout sebagai <em>Horizontal</em> ketika akan mencetak label ini
                </p>
            </td>
            <td>
                <img class="" src="{{ asset('/build/assets/images/paper.png') }}" width="120">

            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="checkbox" class="checkbox" name="sembunyi" id="sembunyi" value="1">
                <small>Sembunyikan Daftar Produk</small>
                | Kode JOB (Jika Ada) : <input type="text" name="kodejob" id="kodejob" />
            </td>
        </tr>
    </table>

    <!-- with pack list -->

    <div class="page left">
        <!-- shipping label  -->
        <div class="address-label-batch-v2-component">
            <table class="address-label-batch-v2-component__header">
                <colgroup>
                    <col style="width: 40%;">
                    <col style="width: 60%;">
                </colgroup>
                <tr>
                    <td>
                        <div class="address-label-batch-v2-component__title">Kurir</div>
                        <strong>{{ $order->shipping->courier_label_alternative }}</strong>

                    </td>
                    <td rowspan="2">
                        @if ($order->shipping->dropshipper_name == NULL)
                        <div style="text-align: right;">
                            {{-- {!! DNS2D::getBarcodeSVG($data->qrcheck, 'QRCODE', 2, 2); !!} --}}
                        </div>

                        @else
                        <h3 style="text-align: right">Label Pengiriman <br></h3>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding-top: 0.5em;">
                        <div class="address-label-batch-v2-component__title">No. Invoice</div>
                        {{ $order->invoice_number }}
                    </td>
                </tr>
                <tr id="job-section" style="display: none;">
                    <td style="padding-top: 0.5em;">
                        <div class="address-label-batch-v2-component__title">Kode JOB / Tracking</div>
                        <span id="job-code"></span>
                    </td>
                </tr>
            </table>
            <hr />
            <table class="address-label-batch-v2-component__body">
                <colgroup>
                    <col style="width: 35%;">
                    <col style="width: 65%;">
                </colgroup>
                <tr>
                    <td class="address-label-batch-v2-component__address-from">
                        <div class="address-label-batch-v2-component__title">Dari</div>
                        <div class="address-label-batch-v2-component__username">
                            {{ $order->shipping->dropshipper_name != null ? $order->shipping->dropshipper_name : store_outlet()->name }}
                        </div>
                        {{-- <div class="address-label-batch-v2-component__username">(azkiahijab.official)</div> --}}
                        <div class="address-label-batch-v2-component__mobile">
                            {{ $order->shipping->dropshipper_phone != null ? $order->shipping->dropshipper_phone : store_outlet()->phone }}
                        </div>
                    </td>
                    <td class="address-label-batch-v2-component__address-to">
                        <div class="address-label-batch-v2-component__shipping">
                            <div class="address-label-batch-v2-component__shipping-fee">
                                <span>Estimasi Ongkos Kirim:</span> {{ $order->shipping_cost_label }}
                            </div>
                            <div class="address-label-batch-v2-component__shipping-weight">
                                <span>Total Berat:</span> {{ $order->total_weight }} gr
                            </div>
                        </div>

                        <div class="address-label-batch-v2-component__title">Kepada</div>

                        <div class="address-label-batch-v2-component__username">{{ $order->shipping->recipient_name }}
                        </div>

                        <div class="address-label-batch-v2-component__mobile">{{ $order->shipping->recipient_phone }}
                        </div>
                        <div class="address-label-batch-v2-component__address">{!! $order->shipping->recipient_address
                            !!}</div>
                    </td>
                </tr>
            </table>

            <hr />
            <div class="address-label-batch-v2-component__footer">
                @if ($order->shipping->dropshipper_name == NULL)
                <div class="address-label-batch-v2-component__shopee-logo">
                    <img class="" style="width: 100px; margin-top: 5px" src="{{ asset('/build/assets/images/logo-color.png') }}" />
                </div>
                @endif

                @if ($order->shipping->dropshipper_name == NULL)
                <div class="address-label-batch-v2-component__footer-title">Saya menjual produk ini kepada {{
                    $order->customer->gender == 'L' ? 'Saudara' : 'Saudari' }} <strong>{{ $order->customer->name
                        }}</strong><br> senilai yang telah ditentukan</div>
                <div class="address-label-batch-v2-component__footer-content">Jika ingin melakukan retur / penukaran
                    produk, harap di simpan no.invoice di label pengiriman ini.<br /></div>
                @else
                <div class="address-label-batch-v2-component__footer-title">
                    Terimakasih Sudah berbelanja di "
                    <strong>
                        {{ $order->shipping->dropshipper_name }}
                    </strong>" Semoga menjadi langganan ya :)
                </div>
                @endif
            </div>

            {{-- <div class="cut-line"><img src="{{ asset('images/scissor.png') }}" width="20" height="27" alt=""
                    class="scissor"></div> --}}
        </div>

        <!-- shipping cut line -->

        <div class="cut-line shipping-label desc">
            <div class="scissors_icon">
                {{-- <img class="" width="20" height="27" src="{{ asset('images/scissor.png') }}"> --}}

            </div>
        </div>
        <!-- pack list -->
        <div class="packlist-component" id="daftarproduk">
            @if ($order->shipping->dropshipper_name == NULL)
            <div class="packlist-component__product-list__ordersn">
                <span class="txt-14 color-gray">No. Invoice</span>&nbsp;
                <span class="txt-14">{{ $order->invoice_number }}</span>
            </div>
            @endif
            <h3>DAFTAR PRODUK</h3>
            <table class="packlist-component__product-list">
                <colgroup>
                    <col style="width: 2.7%">
                    <col style="width: 11.4%">
                    <col style="width: 34.2%">
                    <col style="width: 5.7%">
                </colgroup>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kode</th>
                        <th>Nama Produk</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                    @endphp

                    @foreach ($orderItems as $item)
                    <tr class="packlist-component__product-list__item--has-itemmodel">
                        <td>{{ $no++ }}</td>
                        <td>{{ $item->productVariant->barcode }}</td>
                        <td><b>{{ (($item->name)) }}</b></td>
                        <td class="txt-align-center" style="text-align: center">{{ $item->quantity }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="3" style="text-align: right"><strong>Total &nbsp;&nbsp;&nbsp;</strong></td>
                        <td style="text-align: center"><strong>{{ $order->total_item }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="scissors_icon vertical">
            {{-- <img class="" width="20" height="27" src="{{ asset('images/scissor.png') }}"> --}}
        </div>
    </div>
</body>

@push('styles')
    <style>
        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            line-height: 1.3em;
        }

        body {
            width: 100%;
            display: block;
            margin: 0 auto;
            font-size: 9pt;
            font-family: Lucida Sans Unicode, Lucida Grande, sans-serif;
            color: #000;
            word-wrap: break-word;
            word-break: break-word;
        }

        table {
            width: 100%;
            max-width: 100%;
            border-spacing: 0;
            font-size: 1em;
        }

        td,
        th {
            vertical-align: top;
            text-align: left;
            padding: 0;
            margin: 0;
        }

        hr {
            margin: 0;
            border-top: 2px dashed #bababa;
            border-bottom: 0;
        }

        h3 {
            margin: 27px 0 5px;
        }

        .container {
            width: 11.12in;
            margin: 0.4in auto;
        }

        .page {
            float: left;
            width: 50%;
            min-width: 5in;
            position: relative;
        }

        .page.left {
            padding: 0 2% 0 0.23in;
            border-right: 2px dashed #bababa;
            margin-right: -1px;
        }

        .page.right {
            padding: 0 0.23in 0 2%;
        }

        /* Page Breaker */
        .page-breaker {
            position: relative;
            text-align: center;
            page-break-before: always;
            margin-bottom: 20px;
        }

        @media print {

            .no-print,
            .no-print * {
                display: none !important;
            }

            .container {
                width: 100%;
            }
        }

        @page {
            size: landscape;
            margin: 0.1in 0.2in;
        }

        /* Cut line */
        .cut-line {
            display: none;
        }

        .shipping-label.cut-line {
            position: relative;
            display: block;
            margin-top: 15px;
            left: 0;
            border-bottom: 2px dashed #bababa;
            width: 104%;
            height: 0;
        }

        .shipping-label.desc.cut-line:after {
            content: 'Potong atau lipat pada garis ini';
            font-size: 0.8em;
            margin-left: 1em;
        }

        .scissors_icon {
            transform: rotate(-90deg);
            bottom: -14px;
            vertical-align: middle;
            position: absolute
        }

        .scissors_icon.vertical {
            transform: none;
            display: block;
            position: absolute;
            top: 0;
            right: -12px;
        }

        /* Instruction */
        .instruction {
            color: #757575;
            font-size: 14px;
            line-height: 20px;
            background: #f6f6f6;
            width: 600px;
            display: block;
            margin: 40px auto;
            outline: 2px solid #ccc;
            outline-offset: 4px;
        }

        .instruction .desc strong {
            color: #ff5722;
        }

        .instruction td {
            padding: 10px 20px;
        }

        .instruction td {
            padding: 10px 20px;
        }
    </style>

    <style>
        .address-label-batch-v2-component {
            border: 2px solid #bababa;
            position: relative;
        }

        .address-label-batch-v2-component__header,
        .address-label-batch-v2-component__footer,
        .address-label-batch-v2-component__body {
            padding: 0.05in 0.1in;
            table-layout: fixed;
        }

        .address-label-batch-v2-component__body {
            height: 2in;
            overflow: hidden;
        }

        .address-label-batch-v2-component__title {
            color: #8c8c8c;
            text-transform: uppercase;
        }

        .address-label-batch-v2-component__address-from address-label-batch-v2-component__title,
        .address-label-batch-v2-component__address-to .address-label-batch-v2-component_title {
            margin-bottom: 0.5em;
        }

        .address-label-batch-v2-component__address-from {
            vertical-align: top;
            font-size: 0.9em;
        }

        .address-label-batch-v2-component__address-from .address-label-batch-v2-component__title {
            font-size: 1.11em;
        }

        .address-label-batch-v2-component__address-to {
            vertical-align: bottom;
            font-size: 1.2em;
            font-weight: bold;
            position: relative;
        }

        .address-label-batch-v2-component__address-to .address-label-batch-v2-component__shipping {
            position: absolute;
            top: 0;
            right: 0;
            font-size: 0.8em;
            font-weight: normal;
            text-align: right;
        }

        .address-label-batch-v2-component__address-to .address-label-batch-v2-component__shipping-fee span,
        .address-label-batch-v2-component__address-to .address-label-batch-v2-component__shipping-weight span {
            font-weight: bold;
        }

        .address-label-batch-v2-component__address-to .address-label-batch-v2-component__title {
            font-size: 0.9em;
            font-weight: normal;
        }

        .address-label-batch-v2-component__username {
            font-weight: bold;
        }

        .address-label-batch-v2-component__mobile {
            margin-top: 0.5em;
        }

        .address-label-batch-v2-component__product-list {
            font-size: 0.9em;
            color: #666;
        }

        .address-label-batch-v2-component__product-list td,
        .address-label-batch-v2-component__product-list th {
            padding: 2px 1px;
        }

        .address-label-batch-v2-component__product-list thead th,
        .address-label-batch-v2-component__product-list thead td {
            color: #000;
            border-bottom: 2px solid #bababa;
            word-break: normal;
        }

        .address-label-batch-v2-component__product-list tbody tr+tr td {
            border-top: 2px dashed #bababa;
        }

        .address-label-batch-v2-component__product-list tbody tr:last-child td {
            border-bottom: 1px dashed #bababa;
        }

        .address-label-batch-v2-component__product-list tbody tr+.address-label-batch-v2-component__product-list__itemmodel td,
        .address-label-batch-v2-component__product-list tbody .address-label-batch-v2-component__product-list__itemmodel+.address-label-batch-v2-component__product-list__itemmodel td {
            border-top: none;
        }

        .address-label-batch-v2-component__product-list__ordersn {
            float: right;
            margin-top: 2.2em;
        }

        .address-label-batch-v2-component__comment-header {
            color: #000;
            font-weight: bold;
            margin-right: 1em;
        }

        .address-label-batch-v2-component__comment,
        .address-label-batch-v2-component__footer-content {
            color: #8c8c8c;
        }

        .address-label-batch-v2-component__shopee-logo img {
            float: left;
            margin-right: 10px;
            width: 88pt;
        }

        .address-label-batch-v2-component__footer-title,
        .address-label-batch-v2-component__footer-content {
            padding-left: 125px;
            font-size: 0.9em;
        }
    </style>

    <style>
        .packlist-component__title {
            color: #8c8c8c;
            text-transform: uppercase;
        }

        .packlist-component__address-from .packlist-component__title {
            margin-bottom: 0.5em;
        }

        .packlist-component__address-from .packlist-component__title {
            font-size: 1.11em;
        }

        .packlist-component__address-to .packlist-component__title {
            font-size: 0.9em;
            font-weight: normal;
        }

        .packlist-component__product-list {
            font-size: 0.9em;
            color: #666;
            table-layout: fixed;
            word-wrap: break-word;
            word-break: break-word;
        }

        .packlist-component__product-list td,
        .packlist-component__product-list th {
            padding: 2px 1px;
            vertical-align: middle;
        }

        .packlist-component__product-list thead th,
        .packlist-component__product-list thead td {
            font-weight: bolder;
            color: #000;
            border-bottom: 2px solid #bababa;
            word-break: normal;
        }

        .packlist-component__product-list tbody tr+tr td {
            border-top: 2px dashed #bababa;
        }

        .packlist-component__product-list tbody tr:last-child td {
            border-bottom: 1px dashed #bababa;
        }

        .packlist-component__product-list tbody tr+.packlist-component__product-list__itemmodel td,
        .packlist-component__product-list tbody .packlist-component__product-list__itemmodel+.packlist-component__product-list__itemmodel td {
            border-top: none;
        }

        .packlist-component__product-list__ordersn {
            float: right;
            margin-top: 2.2em;
        }

        .packlist-component__comment-header {
            color: #000;
            font-weight: bold;
            margin-right: 1em;
        }

        .packlist-component__comment {
            color: #8c8c8c;
        }

        .packlist-component__shopee-logo img {
            float: left;
            margin-right: 10px;
            width: 88pt;
        }

        .packlist-component table {
            text-align: left;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("input[name='sembunyi']").change(function (e) {
                e.preventDefault();
                if (this.checked) {
                    $("#daftarproduk").hide();
                } else {
                    $("#daftarproduk").show();
                }
            });

            $("#kodejob").keyup(function (e) {
                if($(this).val() != '') {
                    $("#job-section").show();
                    $("#job-code").html($(this).val());
                } else {
                    $("#job-section").hide();
                }
            });
        });
    </script>
@endpush
