<div>
    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <div class="col-12">
        <h4 class="mb-2">Ubah Password</h4>
    </div>
    <div class="col-12">
        <form wire:submit.prevent="changePassword">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Password Lama</label>
                        <input type="password" class="form-control @error('currentPassword') is-invalid mb-0 @enderror"
                            placeholder="Password Sekarang *" wire:model.defer="currentPassword">
                        @error('currentPassword')
                            <span class="invalid-feedback" role="alert"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
            </div><!-- End .row -->

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Password Baru</label>
                        <input type="password" class="form-control @error('newPassword') is-invalid mb-0 @enderror"
                            placeholder="Password Baru *" wire:model.defer="newPassword">
                        @error('newPassword')
                            <span class="invalid-feedback" role="alert"> {{ $message }} </span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Konfirmasi Password Baru</label>
                        <input type="password" class="form-control @error('newPassword') is-invalid mb-0 @enderror"
                            placeholder="Konfirmasi Password Baru *" wire:model.defer="confirmPassword">
                        @error('confirmPassword')
                            <span class="invalid-feedback" role="alert"> {{ $message }} </span>
                        @enderror
                    </div>

                </div><!-- End .col-sm-6 -->
            </div><!-- End .row -->

            <div class="text-left">
                <button type="submit" class="btn btn-outline-primary-2 btn-minwidth-sm" wire:loading.attr="disabled"
                    wire:click="changePassword">
                    <div wire:loading.class="d-none" wire:target="changePassword">
                        <span>Update Password</span>
                        <i class="icon-long-arrow-right"></i>
                    </div>

                    <div wire:loading wire:target="changePassword">
                        <x-css-spinner class="fa-spin" />
                    </div>
                </button>
            </div><!-- End .text-center -->
        </form>
    </div>
</div>
