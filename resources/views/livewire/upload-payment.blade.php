<div>
    @if ($order->status_paid)
        <span class="text-success"><i class="fa fa-check"></i> Pesanan Sudah Dibayar</span>
    @endif

    @if ($order->status_waiting_payment)
    <button class="btn btn-primary" wire:loading.attr="disabled" wire:click="openPaymentModal">
        <div wire:loading.class="d-none" wire:target="openPaymentModal">
            Upload Bukti Pembayaran
        </div>
        <div wire:loading wire:target="openPaymentModal">
            <i class="fa fa-spinner fa-spin"></i>
        </div>
    </button>

    <div class="modal fade" id="order-payment-modal" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content" role="document">
                <div class="modal-header p-4">
                    <h5 class="modal-title">
                        Upload Bukti Transfer
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
                                    <a href="#">{{ $order?->number }}</a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    Tanggal Pembelian
                                </div>
                                <div class="col-md-8 text-right">
                                    {{ $order?->date_format_id }}
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    Status Transaksi
                                </div>
                                <div class="col-md-8 text-right">
                                    <span
                                        class="badge badge-{{ $order?->status_color }}">{{ $order?->status->description }}</span>
                                </div>
                            </div>
                            <hr>
                            <div class="payment mt-2">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row font-weight-bold">
                                            <div class="col-md-4">
                                                Total Belanja
                                            </div>
                                            <div class="col-md-8 text-right">
                                                {{ $order?->final_price_label }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <x-form wire:submit.prevent="savePayment" has-files>
                                <div class="row">
                                    <div class="col-12">
                                        <x-form-group label="Tujuan Transfer">
                                            <x-select
                                                name="bankAccountId"
                                                :options="[
                                                    [
                                                        'id' => '',
                                                        'account_label' => 'Pilih Bank Tujuan'
                                                    ],
                                                    ...$bankAccounts
                                                ]"
                                                value-field="id"
                                                label-field="account_label"
                                                wire:model="bankAccountId"
                                            ></x-select>
                                            @error('bankAccountId')
                                                <span class="error">{{ $message }}</span>
                                            @enderror
                                        </x-form-group>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <x-form-group label="Upload Bukti Transfer">
                                            <x-file-pond
                                                wire:model="file"
                                                :options="[
                                                    'allowImagePreview' => true
                                                ]"
                                            />
                                            @error('file')
                                                <span class="error">{{ $message }}</span>
                                            @enderror
                                        </x-form-group>
                                    </div>
                                </div>
                            </x-form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" wire:loading.attr="disabled" class="btn btn-success" wire:click="savePayment">
                        <span wire:target="savePayment" wire:loading.class="d-none">
                            Simpan <i class="icon-arrow-right"></i>
                        </span>

                        <div wire:loading wire:target="savePayment">
                            <x-css-spinner class="fa-spin" />
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </div>

    @endif
</div>

@push('scripts')
<script>
    Livewire.on('open-payment-modal', function() {
        $('#order-modal').modal('hide');
        $('#order-payment-modal').modal('show');
    })

    Livewire.on('close-payment-modal', function() {
        $('#order-payment-modal').modal('hide');
        $('.modal-backdrop').remove();
    });
</script>
@endpush
