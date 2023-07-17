<div>
    <nav class="blog-nav">
        <ul class="menu-cat entry-filter justify-content-center">
            <li class="{{ empty($tab) ? 'active' : '' }}"><a href="javascript:void(0);" wire:click="setTab">Semua
                    Pesanan</a>
            </li>
            <li class="{{ $tab == 'ongoing' ? 'active' : '' }}"><a href="javascript:void(0);"
                    wire:click="setTab('ongoing')">Sedang Berlangsung</a></li>
            <li class="{{ $tab == 'completed' ? 'active' : '' }}"><a href="javascript:void(0);"
                    wire:click="setTab('completed')">Selesai</a></li>
            <li class="{{ $tab == 'canceled' ? 'active' : '' }}"><a href="javascript:void(0);"
                    wire:click="setTab('canceled')">Dibatalkan</a></li>
        </ul><!-- End .blog-menu -->
    </nav>
    <div class="row">
        <div class="col-12" wire:loading wire:target="setTab">
            <table class="table table-cart table-mobile ssc">
                <tbody>
                    <tr>
                        <td colspan="2" style="padding: 0;">
                            <span class="ssc-line" style="max-width: 300px;"></span>
                        </td>
                        <td class="text-right">
                            <span class="ssc-line"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="product-col mr-4" width="150">
                            <div class="product">
                                <figure class="product-media ssc-square mb" style="max-width: 150px;">
                                </figure>
                            </div><!-- End .product -->
                        </td>
                        <td class="align-top" width="290">
                            <h3 class="product-title ml-4">
                                <a href="#">
                                    <span class="ssc-line" style="max-width: 300px;"></span> <br>
                                    <span class="ssc-line" style="max-width: 150px;"></span>
                                </a>
                                <p class="mt-1">
                                    <span class="ssc-line" style="max-width: 50px;"></span>
                                </p>
                                <p class="mt-1">
                                    <span class="ssc-line" style="max-width: 50px;"></span>
                                </p>
                                <p class="mt-2">
                                    <span class="ssc-line" style="max-width: 50px;"></span>
                                </p>
                            </h3><!-- End .product-title -->
                        </td>
                        <td class="price-col text-nowrap align-top text-right">
                            <span class="ssc-line pull-right" style="max-width: 150px;"></span> <br>
                            <span class="ssc-line pull-right" style="max-width: 100px;"></span> <br>
                            <span class="ssc-line pull-right" style="max-width: 100px;"></span>
                        </td>

                    </tr>
                    <tr>
                        <td class="align-top" colspan="2">
                        </td>
                        <td class="text-right">
                            <div class="ssc-square mb" style="height: 35px"></div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-cart table-mobile ssc">
                <tbody>
                    <tr>
                        <td colspan="2" style="padding: 0;">
                            <span class="ssc-line" style="max-width: 300px;"></span>
                        </td>
                        <td class="text-right">
                            <span class="ssc-line"></span>
                        </td>
                    </tr>
                    <tr>
                        <td class="product-col mr-4" width="150">
                            <div class="product">
                                <figure class="product-media ssc-square mb" style="max-width: 150px;">
                                </figure>
                            </div><!-- End .product -->
                        </td>
                        <td class="align-top" width="290">
                            <h3 class="product-title ml-4">
                                <a href="#">
                                    <span class="ssc-line" style="max-width: 300px;"></span> <br>
                                    <span class="ssc-line" style="max-width: 150px;"></span>
                                </a>
                                <p class="mt-1">
                                    <span class="ssc-line" style="max-width: 50px;"></span>
                                </p>
                                <p class="mt-1">
                                    <span class="ssc-line" style="max-width: 50px;"></span>
                                </p>
                                <p class="mt-2">
                                    <span class="ssc-line" style="max-width: 50px;"></span>
                                </p>
                            </h3><!-- End .product-title -->
                        </td>
                        <td class="price-col text-nowrap align-top text-right">
                            <span class="ssc-line pull-right" style="max-width: 150px;"></span> <br>
                            <span class="ssc-line pull-right" style="max-width: 100px;"></span> <br>
                            <span class="ssc-line pull-right" style="max-width: 100px;"></span>
                        </td>

                    </tr>
                    <tr>
                        <td class="align-top" colspan="2">
                        </td>
                        <td class="text-right">
                            <div class="ssc-square mb" style="height: 35px"></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-12" wire:loading.class="d-none" wire:target="setTab">
            @forelse ($orders as $order)
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
                            <td class="text-right">
                                @if ($order->statusCanceled())
                                    <s>
                                        <x-heroicon-o-hashtag style="max-width: 15px;" />{{ $order->number }}
                                    </s>
                                @else
                                    <a href="#" wire:click="show({{ $order->id }})">
                                        <x-heroicon-o-hashtag style="max-width: 15px;" />
                                        {{ $order->number }}
                                    </a>
                                @endif
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
                            <td class="price-col text-nowrap align-top text-right">
                                <h3 class="product-title">Total Pembayaran</h3>
                                <h3 class="product-title">{{ $order->final_price_label }}</h3>
                                <p class="mt-2">
                                    <span
                                        class="badge badge-{{ $order->status_color }}">{{ $order->status->description }}</span>
                                </p>

                                @if ($order->customer_cancelable)
                                    <button class="btn mb-1 btn-outline-danger btn-sm mt-3 p-2 pr-4 m-0"
                                        wire:click="openCancelDialog({{ $order->id }})"><i class="icon-close"></i>
                                        Batalkan Pesanan</button>
                                @endif
                                <br>
                            </td>

                        </tr>
                        @if ($order->status->value === App\Enums\OrderStatus::WaitingPayment)
                            <tr>
                                <td class="price-col align-top" colspan="2">
                                    Segera lakukan pembayaran dalam 1 x 24 jam, atau pesanan Anda akan Otomatis
                                    Dibatalkan.
                                </td>
                                <td class="text-right">
                                    <button class="btn btn-primary" wire:loading.attr="disabled" wire:click="showPayment({{ $order->id }})">
                                        <div wire:loading.class="d-none" wire:target="showPayment({{ $order->id }})">
                                            Upload Bukti Pembayaran
                                        </div>
                                        <div wire:loading wire:target="showPayment({{ $order->id }})">
                                            <i class="fa fa-spinner fa-spin"></i>
                                        </div>
                                    </button>
                                </td>
                            </tr>
                        @else
                            <tr>
                                @if (!$order->trackable)
                                    <td class="align-top" colspan="2">
                                        @if ($order->statusWaitingConfirmation())
                                        <i>Menunggu konfirmasi admin</i>
                                        @endif
                                    </td>
                                @endif
                                <td class="text-right text-nowrap" @if ($order->trackable || $order->is_completed)
                                    colspan="3"
                                @endif>
                                    @if ($order->trackable)
                                        <button class="btn btn-success mr-2" wire:loading.attr="disabled" wire:click="openCompleteDialog({{ $order->id }})">
                                            <div wire:loading.class="d-none" wire:target="openCompleteDialog({{ $order->id }})">Selesaikan Pesanan</div>
                                            <div wire:loading wire:target="openCompleteDialog({{ $order->id }})">
                                                <i class="fa fa-spinner fa-spin"></i>
                                            </div>
                                        </button>
                                        <button class="btn btn-info mr-2" wire:loading.attr="disabled" wire:click="trackingPackage({{ $order->id }})">
                                            <div wire:loading.class="d-none" wire:target="trackingPackage({{ $order->id }})">Lacak Paket</div>
                                            <div wire:loading wire:target="trackingPackage({{ $order->id }})">
                                                <i class="fa fa-spinner fa-spin"></i>
                                            </div>
                                        </button>
                                    @endif

                                    @if ($order->statusCompleted())
                                        <button class="btn btn-success mr-2" wire:loading.attr="disabled" wire:click="openReviewModal({{ $order->id }})">
                                            <div wire:loading.class="d-none" wire:target="openReviewModal({{ $order->id }})">Ulas Pesanan</div>
                                            <div wire:loading wire:target="openReviewModal({{ $order->id }})">
                                                <i class="fa fa-spinner fa-spin"></i>
                                            </div>
                                        </button>
                                    @endif

                                    <button class="btn btn-outline-dark" wire:loading.attr="disabled" wire:click="show({{ $order->id }})">
                                        <div wire:loading.class="d-none" wire:target="show({{ $order->id }})">Lihat Detail</div>
                                        <div wire:loading wire:target="show({{ $order->id }})">
                                            <i class="fa fa-spinner fa-spin"></i>
                                        </div>
                                    </button>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            @empty
                <div class="text-center">
                    <x-lucide-package-open class="mt-5 mb-2" style="max-width: 150px;" />
                    <p>
                    <h5>Belum ada data</h5>
                    </p>
                </div>
            @endforelse
        </div>
    </div>

    @if ($detail && $detail->customer_cancelable)
        <div id="cancel-confirm-dialog" class="white-popup mfp-hide fadeIn">
            <div class="text-center">
                <h5>Yakin untuk membatalkan pesanan ini ?</h5>
                <button type="button" class="btn btn-danger" id="cancel-confirm-button" wire:click="cancelOrder">
                    <span wire:target="cancelOrder" wire:loading.class="d-none">
                        Ya, Batalkan Pesanan
                    </span>

                    <div wire:loading wire:target="cancelOrder">
                        <i class="fa fa-spinner fa-spin"></i>
                    </div>
                </button>
                <button type="button" class="btn btn-outline-dark" id="cancel-cancel"
                    onclick="$.magnificPopup.close()">
                    <span>Tutup</span>
                </button>
            </div>
        </div>
    @endif

    @include('livewire.account.partials.order-modal')

    @include('livewire.account.partials.order-payment')

    @include('livewire.account.partials.order-tracking-modal')

    @include('livewire.account.partials.order-review-modal')

    <div id="complete-order-dialog" class="white-popup mfp-hide">
        <div class="text-center">
            <h5>Selesaikan Pesanan ?</h5>
            <button type="button" class="btn btn-success" id="complete-order-button" wire:click="complete">
                Ya, Selesaikan
            </button>
            <button type="button" class="btn btn-outline-dark" id="cancel-complete-button" onclick="$.magnificPopup.close()">
                Tutup
            </button>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        Livewire.on('close-cancel-dialog', function() {
            $.magnificPopup.close();
        })

        window.addEventListener('open-cancel-dialog', event => {
            $.magnificPopup.open({
                items: {
                    src: '#cancel-confirm-dialog',
                    type: 'inline'
                }
            });
        })

        Livewire.on('close-complete-dialog', function() {
            $.magnificPopup.close();
        })

        window.addEventListener('open-complete-dialog', event => {
            $.magnificPopup.open({
                items: {
                    src: '#complete-order-dialog',
                    type: 'inline'
                }
            });
        })
    </script>
@endpush
