<div>
    <main class="main">
        <nav aria-label="breadcrumb" class="breadcrumb-nav mb-2">
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">Peta Mitra</li>
                </ol>
            </div><!-- End .container -->
        </nav><!-- End .breadcrumb-nav -->

        <div class="page-content">
            <div id="map" ></div>
        </div>
    </main>
</div>

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <style>.popup-partner { font-size: 13px !important; }</style>
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        // Creating map options
        let mapOptions = {
            center: [0.197, 118.257],
            zoom: 5
        }

        // Creating a map object
        let map = new L.map('map', mapOptions);

        // Creating a Layer object
        let layer = new L.TileLayer(`http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png`);

        // Adding layer to the map
        map.addLayer(layer);

        const popupContent = function (partner) {
            return `<b>${partner.store_name || partner.name}</b>
                <hr style="margin:0;line-height:0" /> <br />
                <b>Telp/Hp: </b> ${partner.phone} <br />
                ${partner.email ? `<b>Email: </b> ${partner.email} <br />` : ''}
                ${partner.address ? `<b>Alamat: </b> ${partner.address}` : ''}`
        }

        document.addEventListener('DOMContentLoaded', function() {
            axios.get('/web-api/partners').then(response => {
                const distributor = response.data.distributor
                const agen = response.data.agen

                distributor.forEach(partner => {
                    let pinIcon = L.icon({
                        iconUrl: '/build/assets/images/purple_icon_pin.png',
                        iconSize: [25, 32],
                        iconAnchor: [25, 32],
                        popupAnchor: [-12, -32]
                    });

                    let marker = new L.marker([partner.latitude, partner.longitude], {
                        icon: pinIcon
                    });

                    marker.addTo(map)
                    .bindPopup(popupContent(partner), {
                        className: "popup-partner",
                    });
                });

                agen.forEach(partner => {
                    let pinIcon = L.icon({
                        iconUrl: '/build/assets/images/pink_icon_pin.png',
                        iconSize: [25, 32],
                        iconAnchor: [25, 32],
                        popupAnchor: [-12, -32]
                    });

                    let marker = new L.marker([partner.latitude, partner.longitude], {
                        icon: pinIcon
                    });

                    marker.addTo(map)
                        .bindPopup(popupContent(partner), {
                            keepInView: true,
                            className: 'popup-partner',
                        });
                });
            })
        })
    </script>
@endpush
