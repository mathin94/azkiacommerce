<div>
   @if ($detail && $manifests)
       <div class="modal fade" id="order-tracking-modal" tabindex="-2" role="dialog" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document" style="max-width: 800px;">
                <div class="modal-content" role="document">
                    <div class="modal-header p-4">
                        <h5 class="modal-title">
                            Detail Pengiriman
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="icon-close"></i></span>
                        </button>
                    </div>
                    <div class="modal-body p-5">
                        <div class="row">
                            <div class="col-12">
                                <h3 class="product-title">Info Pengiriman</h3>
                                <div class="shipping mt-2">
                                    <div class="row">
                                        <div class="col-12">
                                            <table class="">
                                                <tr>
                                                    <td width="80">Kurir</td>
                                                    <td width="1">:</td>
                                                    <td class="text-left">
                                                        <span class="pl-3">{{ $detail->shipping->courier_label_alternative
                                                            }}</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="80">No. Resi</td>
                                                    <td width="1">:</td>
                                                    <td class="text-left">
                                                        <span class="pl-3">{{ $detail->shipping->receipt_number ?? 'Belum ada
                                                            resi' }}</span>
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
                                <h3 class="product-title">Detail Pengiriman</h3>
                                <div class="payment mt-2">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="text-nowrap" width="25%">
                                                    Tanggal</th>
                                                <th>Status
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($manifests as $key => $item)
                                            <tr>
                                                <td class="text-nowrap">{{ $item['timestamp'] }}
                                                </td>
                                                <td>{{ $item['description'] }}
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="2" class="text-center">
                                                    <i>Belum ada data pengiriman</i>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><!-- End .modal-body -->
                    </div><!-- End .modal-content -->
                </div><!-- End .modal-dialog -->
            </div><!-- End .modal -->
   @endif

    @push('scripts')
        <script>
            Livewire.on('open-tracking-modal', function() {
                $('#order-tracking-modal').modal('show');
            })
        </script>
    @endpush

</div>
