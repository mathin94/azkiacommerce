<div>
    <div class="row">
        @if (session()->has('info'))
        <div class="col-12">
            <div class="alert alert-info mb-3">
                {{ session('info') }}
            </div>
        </div>
        @endif

        @if (session()->has('warning'))
        <div class="col-12">
            <div class="alert alert-warning mb-3">
                {{ session('warning') }}
            </div>
        </div>
        @endif
        <div class="col-6">
            <h5>Daftar Alamat</h5>
        </div>
        <div class="col-6 text-right mb-2">
            <button class="btn btn-square btn-outline-dark" wire:click="openAddDialog"><i class="fa fa-plus"></i> Tambah
                Alamat</button>
        </div>
        <div class="col-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>Label</th>
                        <th width="50%">Alamat Lengkap</th>
                        <th width="5%"></th>
                    </tr>
                </thead>
                @foreach ($addresses as $address)
                    <tr>
                        <td>
                            <span>{{ $address->label }}</span> <br>
                            {!! $address->status_badge !!}
                        </td>
                        <td>
                            <p>
                                <span class="font-weight-bold">Nama Penerima:</span> {{ $address->recipient_name }}
                            </p>
                            <p>
                                <span class="font-weight-bold">Telp Penerima:</span> {{ $address->recipient_phone }}
                            </p>
                            <p class="mt-1">
                                <span class="font-weight-bold">Alamat Penerima:</span> {{ $address->full_address }}
                            </p>
                        </td>
                        <td class="pl-2">
                            <button class="btn mb-1 border-bottom" data-target="#edit-dialog" data-toggle="modal"
                                wire:click="edit({{ $address->id }})"><i class="fa fa-edit"></i> Edit</button>
                            <br>
                            <button class="btn border-bottom" wire:click="openDeleteDialog({{ $address->id }})"><i
                                    class="fa fa-trash"></i> Hapus</button>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>
        <div class="col-12 mt-2">
            {!! $addresses->links() !!}
        </div>
    </div>

    <div id="delete-confirm-dialog" class="white-popup mfp-hide fadeIn">
        <div class="text-center">
            <h5>Yakin untuk menghapus alamat ini ?</h5>
            <button type="button" class="btn btn-danger" id="delete-confirm-button" wire:click="delete">
                Ya, Hapus
            </button>
            <button type="button" class="btn btn-outline-dark" id="cancel-delete" onclick="$.magnificPopup.close()">
                Batalkan
            </button>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="edit-dialog" tabindex="-1" role="dialog"
        aria-labelledby="edit-dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="modal-header">
                            <h5 class="modal-title p-3">{{ $formTitle }}</h5>
                            <button type="button" class="close pa-2" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true"><i class="icon-close"></i></span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-box">
                                <form wire:submit.prevent="submit">
                                    <div class="form-group">
                                        <label for="label">Label Alamat *</label>
                                        <input type="hidden" wire:model="id">
                                        <input type="text"
                                            class="form-control @error('label') is-invalid mb-0 @enderror"
                                            placeholder="Label Alamat*" wire:model="label" value="{{ $this->label }}">
                                        @error('label')
                                            <span class="invalid-feedback" role="alert"> {{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="recipientName">Nama Penerima *</label>
                                        <input type="text"
                                            class="form-control @error('recipientName') is-invalid mb-0 @enderror"
                                            placeholder="Nama Penerima" wire:model="recipientName">
                                        @error('recipientName')
                                            <span class="invalid-feedback" role="alert"> {{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="recipientPhone">Telp Penerima *</label>
                                        <input type="text"
                                            class="form-control @error('recipientPhone') is-invalid mb-0 @enderror"
                                            placeholder="Telp Penerima" wire:model="recipientPhone">
                                        @error('recipientPhone')
                                            <span class="invalid-feedback" role="alert"> {{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="">Kecamatan *</label>
                                        <livewire:subdistrict-search>
                                            @error('subdistrictId')
                                                <span class="invalid-feedback d-block" role="alert"> {{ $message }}
                                                </span>
                                            @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="street">Alamat Lengkap</label>
                                        <textarea class="form-control @error('street') is-invalid mb-0 @enderror" wire:model="street" rows="3"></textarea>
                                        @error('street')
                                            <span class="invalid-feedback" role="alert"> {{ $message }} </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="postCode">Kode POS</label>
                                        <input type="text" class="form-control" placeholder="Kode POS"
                                            wire:model="postCode">
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="isMain"
                                            id="isMain" wire:model="isMain">
                                        <label class="custom-control-label" for="isMain">Jadikan alamat
                                            utama</label>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <span><i class="icon-close"></i> Batal</span>
                            </button>
                            <button type="button" class="btn btn-primary" wire:click="submit">
                                <span wire:target="submit" wire:loading.class="d-none">
                                    <i class="fa fa-save"></i> Simpan
                                </span>

                                <span wire:loading wire:target="submit">
                                    <x-css-spinner class="fa-spin" />
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@push('scripts')
    <script>
        Livewire.on('close-delete-dialog', function() {
            $.magnificPopup.close();
        })

        Livewire.on('close-edit-dialog', function() {
            $("#edit-dialog").modal('hide');
        })

        window.addEventListener('open-delete-dialog', event => {
            $.magnificPopup.open({
                items: {
                    src: '#delete-confirm-dialog',
                    type: 'inline'
                }
            });
        })

        window.addEventListener('open-dialog', event => {
            $("#edit-dialog").modal('show');
        })
    </script>
@endpush
