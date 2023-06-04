<div class="modal fade" id="edit-address-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="icon-close"></i></span>
                </button>

                <form wire:submit.prevent="saveAddress">
                    <div class="form-group">
                        <label for="recipient-name">Nama Penerima</label>
                        <input type="text" class="form-control" wire:model.lazy="address.recipient_name"
                            id="recipient-name" placeholder="Nama Penerima">
                    </div>
                </form>
            </div><!-- End .modal-body -->
        </div><!-- End .modal-content -->
    </div><!-- End .modal-dialog -->
</div><!-- End .modal -->
