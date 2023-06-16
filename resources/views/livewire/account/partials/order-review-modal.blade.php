<div>
    @if ($detail)
        <div class="modal fade" id="order-review-modal" tabindex="-2" role="dialog" aria-hidden="true" wire:ignore.self>
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document" style="max-width: 800px;">
                <div class="modal-content" role="document">
                    <div class="modal-header p-4">
                        <h5 class="modal-title">
                            Ulas Pesanan
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="icon-close"></i></span>
                        </button>
                    </div>
                    <div class="modal-body p-5">
                        <div class="row">
                            <div class="col-12">
                                <div class="product-container mt-2">
                                    @foreach ($detail->items as $item)
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="product">
                                                    <figure class="product-media" style="max-width: 100px;">
                                                        <a href="#">
                                                            <img src="{{ $item->product_image_url }}"
                                                                alt="{{ $item->name }}">
                                                        </a>
                                                    </figure>
                                                </div><!-- End .product -->
                                            </div>
                                            <div class="col-md-10">
                                                <a href="#" style="color: black;">
                                                    {{ $item->alternate_name }} <br>
                                                </a>
                                                @if ($item->review)
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="ratings-container">
                                                                <div class="ratings">
                                                                    <div class="ratings-val" style="width: {{ $item->rating_percentage }}%;"></div><!-- End .ratings-val -->
                                                                </div><!-- End .ratings -->
                                                            </div>
                                                            <div class="review-content">
                                                                <p>{{ $item->review->review }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @else
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="rating">
                                                            <input wire:model="reviews.{{ $item->id}}.rating" type="radio" name="star-{{ $item->id }}" id="star-{{ $item->id }}-5" name="rating" value="5" /><label
                                                                for="star-{{ $item->id }}-5"></label>
                                                            <input wire:model="reviews.{{ $item->id}}.rating" type="radio" name="star-{{ $item->id }}" id="star-{{ $item->id }}-4"
                                                                name="rating" value="4" /><label for="star-{{ $item->id }}-4"></label>
                                                            <input wire:model="reviews.{{ $item->id}}.rating" type="radio" name="star-{{ $item->id }}" id="star-{{ $item->id }}-3"
                                                                name="rating" value="3" /><label for="star-{{ $item->id }}-3"></label>
                                                            <input wire:model="reviews.{{ $item->id}}.rating" type="radio" name="star-{{ $item->id }}" id="star-{{ $item->id }}-2"
                                                                name="rating" value="2" /><label for="star-{{ $item->id }}-2"></label>
                                                            <input wire:model="reviews.{{ $item->id}}.rating" type="radio" name="star-{{ $item->id }}" id="star-{{ $item->id }}-1"
                                                                name="rating" value="1" /><label for="star-{{ $item->id }}-1"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div>
                                                            <label for="">Tulis Ulasan</label>
                                                            <textarea class="form-control" rows="3" wire:model="reviews.{{ $item->id }}.review"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="pull-right">
                                                    <button class="btn btn-success" wire:click="saveReview({{ $item->id }})">
                                                        <span wire:loading.class="d-none" wire:target="saveReview({{ $item->id }})">Simpan Ulasan</span>
                                                        <div wire:loading wire:target="saveReview({{ $item->id }})">
                                                            <x-css-spinner class="fa-spin" />
                                                        </div>
                                                    </button>
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        <hr>
                                    @endforeach
                                </div>
                            </div>
                        </div><!-- End .modal-body -->
                    </div><!-- End .modal-content -->
                </div><!-- End .modal-dialog -->
            </div><!-- End .modal -->
    @endif

    @push('scripts')
        <script>
            Livewire.on('open-review-modal', function() {
                $('#order-review-modal').modal('show');
            })
        </script>
    @endpush

</div>
