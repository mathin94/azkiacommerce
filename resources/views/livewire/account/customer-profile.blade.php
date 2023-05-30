<div>
    <div class="row">
        <div class="col-12">
            <div class="col-12">
                <h4 class="mb-2">Profil Pengguna</h4>
            </div>
            <div class="col-12 mb-2">
                <form wire:submit.prevent="updateProfile">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nama Lengkap</label>
                                <input type="text" class="form-control @error('fullName') is-invalid mb-0 @enderror"
                                    placeholder="Nama Lengkap *" wire:model="fullName">
                                @error('fullName')
                                    <span class="invalid-feedback" role="alert"> {{ $message }} </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Alamat Email</label>
                                <input type="email" class="form-control @error('email') is-invalid mb-0 @enderror"
                                    placeholder="Alamat Email *" wire:model="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert"> {{ $message }} </span>
                                @enderror
                            </div>
                        </div><!-- End .col-sm-6 -->
                    </div><!-- End .row -->

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>No. Telp / Hp</label>
                                <input type="text" class="form-control @error('phone') is-invalid mb-0 @enderror"
                                    placeholder="No. Telp / Hp *" wire:model="phone">
                                @error('phone')
                                    <span class="invalid-feedback" role="alert"> {{ $message }} </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Gender</label>
                                <select class="form-control @error('gender') is-invalid mb-0 @enderror" name="gender"
                                    wire:model="gender">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    @foreach ($genderOptions as $value => $name)
                                        <option value="{{ $value }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('gender')
                                    <span class="invalid-feedback" role="alert"> {{ $message }} </span>
                                @enderror
                            </div>
                        </div><!-- End .col-sm-6 -->
                    </div><!-- End .row -->

                    <div class="text-left">
                        <button type="button" class="btn btn-outline-primary-2 btn-minwidth-sm"
                            wire:loading.attr="disabled" wire:click="updateProfile">
                            <div wire:loading.class="d-none" wire:target="updateProfile">
                                <span>Update Profil</span>
                                <i class="icon-long-arrow-right"></i>
                            </div>

                            <div wire:loading wire:target="updateProfile">
                                <x-css-spinner class="fa-spin" />
                            </div>
                        </button>
                    </div><!-- End .text-center -->
                </form>
            </div>
        </div>

        <div class="col-12">
            <livewire:account.change-password-form />
        </div>
    </div><!-- End .row -->
</div>
