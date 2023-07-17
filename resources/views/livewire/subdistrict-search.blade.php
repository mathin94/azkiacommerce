<div class="{{ $componentClass }}">
    <div id="virtual-select-list" wire:ignore style="width: 100%;"></div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let searchSubdistrict = (searchValue, virtualSelect) => {
                axios.get('/web-api/subdistricts?search=' + searchValue).then((response) => {

                    let options = []

                    response.data.forEach((item) => {
                        options.push({
                            label: `${item.name} - ${item.city_type} ${item.city_name} - ${item.province_name}`,
                            value: item.id
                        })
                    })

                    virtualSelect.setServerOptions(options);
                });
            }

            VirtualSelect.init({
                ele: '#virtual-select-list',
                placeholder: 'Pilih Kecamatan',
                search: true,
                onServerSearch: searchSubdistrict,
                maxWidth: "100%",
                searchDelay: 500,
                noSearchResultsText: 'Data tidak ditemukan',
            });

            let selectedSubdistrict = document.querySelector('#virtual-select-list');

            selectedSubdistrict.addEventListener('change', (e) => {
                let data = selectedSubdistrict.value

                Livewire.emit('changeSubdistrict', {
                    id: data
                })
            })
        })

        window.addEventListener('set-subdistrict', event => {
            document.querySelector('#virtual-select-list').setOptions([{
                label: event.detail.name_label,
                value: event.detail.id
            }])

            document.querySelector('#virtual-select-list').setValue(event.detail.id)
        })

        window.addEventListener('clear-subdistrict', event => {
            document.querySelector('#virtual-select-list').setOptions([])
            document.querySelector('#virtual-select-list').setValue(null)
        })
    </script>
@endpush
