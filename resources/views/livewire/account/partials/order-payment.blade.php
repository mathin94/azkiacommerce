<div>
    @if ($orderPayment)
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
                                        <a href="#">{{ $orderPayment->number }}</a>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        Tanggal Pembelian
                                    </div>
                                    <div class="col-md-8 text-right">
                                        {{ $orderPayment->date_format_id }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        Status Transaksi
                                    </div>
                                    <div class="col-md-8 text-right">
                                        <span
                                            class="badge badge-{{ $orderPayment->status_color }}">{{ $orderPayment->status->description }}</span>
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
                                                    {{ $orderPayment->final_price_label }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <form wire:submit.preven="savePayment">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Tujuan Transfer</label>
                                                <select wire:model="bankAccountId" class="form-control">
                                                    <option value="">Pilih Bank</option>
                                                    @foreach ($bankAccounts as $bankAccount)
                                                        <option value="{{ $bankAccount->id }}">
                                                            {{ $bankAccount->account_label }}
                                                        </option>
                                                    @endforeach
                                                </select>

                                                @error('bankAccountId')
                                                    <span class="error">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label>Upload Bukti Transfer</label>
                                                <input type="file" class="form-control-file" wire:model="file">
                                                @error('file')
                                                    <span class="error">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" wire:click="savePayment">
                            <span wire:target="savePayment" wire:loading.class="d-none">
                                <i class="fa fa-upload"></i> Upload
                            </span>

                            <div wire:loading wire:target="savePayment">
                                <x-css-spinner class="fa-spin" />
                            </div>
                        </button>
                    </div>
                </div>
            </div>
    @endif

    @push('scripts')
        <script>
            Livewire.on('open-payment-modal', function() {
                $('#order-modal').modal('hide');
                $('#order-payment-modal').modal('show');
            })

            Livewire.on('close-payment-modal', function() {
                $('#order-payment-modal').modal('hide');
            });
        </script>
    @endpush

</div>
