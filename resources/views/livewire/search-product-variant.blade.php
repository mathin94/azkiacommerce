<div>
    <form action="" class="mb-3">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <div id="virtual-select-list" wire:ignore style="width: 100%;"></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-white">Rp.</span>
                        </div>
                        <input type="text" placeholder="Harga" class="form-control bg-white text-right" wire:model="price" readonly>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <div class="input-group">
                        <input type="text" placeholder="Stok" class="form-control bg-white" wire:model="stock" readonly>
                        <div class="input-group-append">
                            <span class="input-group-text bg-white">Pcs</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2">
               <div class="form-group">
                    <input
                        type="number"
                        placeholder="Jumlah"
                        id="quantity"
                        class="form-control bg-white"
                        wire:model="quantity"
                        wire:keydown.enter="addToCart"
                    />
               </div>
            </div>
        </div>
    </form>
    <hr>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let searchProductVariant = (searchValue, virtualSelect) => {
                axios.get('/web-api/products?search=' + searchValue).then((response) => {

                    let options = []

                    response.data.forEach((item) => {
                        options.push({
                            label: item.name,
                            value: item.id
                        })
                    })

                    virtualSelect.setServerOptions(options);
                });
            }

            VirtualSelect.init({
                ele: '#virtual-select-list',
                placeholder: 'Cari Produk',
                search: true,
                onServerSearch: searchProductVariant,
                maxWidth: "100%",
                searchDelay: 500,
                noSearchResultsText: 'Data tidak ditemukan',
            });

            let selectedProductVariant = document.querySelector('#virtual-select-list');

            selectedProductVariant.addEventListener('change', (e) => {
                let productVariantId = selectedProductVariant.value

                if (productVariantId) {
                    Livewire.emit('changeProductVariant', {
                        id: selectedProductVariant.value
                    })
                }
            })

            selectedProductVariant.addEventListener('reset', event => {
                Livewire.emit('clearProductVariant');
            });

            Livewire.on('focusToQty', function () {
                console.log("FOCUS")
                document.getElementById('quantity').focus()
            })
        })

        Livewire.on('clearSearch', function () {
            document.querySelector('#virtual-select-list').setOptions([])
            document.querySelector('#virtual-select-list').setValue(null)
        })
    </script>
@endpush
