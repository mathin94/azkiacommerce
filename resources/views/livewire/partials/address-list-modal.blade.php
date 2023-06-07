    <div class="modal fade" id="addresses-modal" tabindex="-1" role="dialog" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content" role="document">
                <div class="modal-body p-5">
                    <button type="button" class="close mb-2" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="icon-close"></i></span>
                    </button>
                    <h3 class="title text-center">
                        Pilih Alamat Pengiriman
                    </h3>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            @foreach ($addresses as $address)
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td class="pr-3">
                                                <span>{{ $address->label }}</span>
                                                {!! $address->status_badge !!}
                                                <p>
                                                    <span class="font-weight-bold">{{ $address->recipient_name }}</span>
                                                </p>
                                                <p>
                                                    {!! $address->full_address !!}
                                                </p>
                                            </td>
                                            <td width="1" class="text-nowrap">
                                                @if ($shippingAddress->id !== $address->id)
                                                    <button class="btn btn-success btn-round"
                                                        style="min-width: 10px">Pilih</button>
                                                @else
                                                    <i class="fa fa-check text-success"></i> <span
                                                        class="text-success">Terpilih</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            @endforeach
                        </div>
                        <div class="col-12">
                            {!! $addresses->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            Livewire.on('open-address-modal', function() {
                $('#addresses-modal').modal('show');
            })

            Livewire.on('close-payment-modal', function() {
                $('#addresses-modal').modal('hide');
            });
        </script>
    @endpush
