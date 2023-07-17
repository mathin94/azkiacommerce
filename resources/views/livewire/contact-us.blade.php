<div>
    <main class="main">
        <div class="page-header text-center" style="background-image: url('/build/assets/images/bg-login.jpg')">
            <div class="container">
                <h1 class="page-title">Hubungi Kami</h1>
            </div><!-- End .container -->
        </div>
        <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Hubungi Kami</li>
                </ol>
            </div><!-- End .container -->
        </nav>

        <div class="page-content">
            <div id="map" class="mb-5" style="position: relative; overflow: hidden;"></div>

            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <div class="contact-box text-center">
                            <h3>Alamat Toko Pusat</h3>

                            <address>{{ store()->address }}</address>
                        </div><!-- End .contact-box -->
                    </div><!-- End .col-md-4 -->

                    <div class="col-md-4">
                        <div class="contact-box text-center">
                            <h3>Hubungi Kami Melalui</h3>

                            <div><a href="mailto:#">{{ store()->email }}</a></div>
                            <div><a href="tel:#">{{ store()->phone }}</a></div>
                        </div><!-- End .contact-box -->
                    </div><!-- End .col-md-4 -->

                    <div class="col-md-4">
                        <div class="contact-box text-center">
                            <h3>Social Media Kami</h3>

                            <div class="social-icons social-icons-color justify-content-center">
                                <a href="{{ site()->facebook_link }}" class="social-icon social-facebook"
                                    title="Facebook" target="_blank">
                                    <x-bi-facebook />
                                </a>
                                <a href="{{ site()->whatsapp_link }}"
                                    class="social-icon social-whatsapp" title="Whatsapp" target="_blank">
                                    <x-bi-whatsapp />
                                </a>
                                <a href="{{ site()->instagram_link }}"
                                    class="social-icon social-instagram" title="Instagram" target="_blank"><x-bi-instagram /></a>
                                <a href="{{ site()->instagram_link }}" class="social-icon social-tiktok" title="TikTok" target="_blank">
                                    <x-bi-tiktok />
                                </a>
                            </div><!-- End .soial-icons -->
                        </div><!-- End .contact-box -->
                    </div><!-- End .col-md-4 -->
                </div><!-- End .row -->

                <hr class="mt-3 mb-5 mt-md-1">
                <div class="touch-container row justify-content-center">
                    <div class="col-md-9 col-lg-7">
                        <div class="text-center">
                        <h2 class="title mb-1">Butuh Bantuan ?</h2><!-- End .title mb-2 -->
                        <p class="lead text-primary">
                            Jika Anda membutuhkan informasi lebih lanjut, silakan mengisi form yang telah kami sediakan atau menghubungi kami melalui email dan nomor yang tersedia.
                        </p><!-- End .lead text-primary -->
                        </div><!-- End .text-center -->

                        <form action="#" class="contact-form mb-2">
                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="cname" class="sr-only">Nama Lengkap</label>
                                    <input type="text" class="form-control" id="cname" wire:model="name" placeholder="Nama Lengkap *" required="">
                                </div><!-- End .col-sm-4 -->

                                <div class="col-sm-4">
                                    <label for="cemail" class="sr-only">Alamat Email</label>
                                    <input type="email" class="form-control" id="cemail" wire:model="email" placeholder="Alamat Email *" required="">
                                </div><!-- End .col-sm-4 -->

                                <div class="col-sm-4">
                                    <label for="cphone" class="sr-only">Telp / Hp</label>
                                    <input type="tel" class="form-control" id="cphone" wire:model="phone" placeholder="No. Telp / Hp">
                                </div><!-- End .col-sm-4 -->
                            </div><!-- End .row -->

                            <label for="csubject" class="sr-only">Subjek Pesan</label>
                            <input type="text" class="form-control" id="csubject" placeholder="Subjek Pesan" wire:model="subject">

                            <label for="cmessage" class="sr-only">Isi Pesan</label>
                            <textarea class="form-control" cols="30" rows="4" id="cmessage" required="" placeholder="Isi Pesan *" wire:model="message"></textarea>

                            <div class="text-center">
                                <button type="button" wire:click="sendMessage" class="btn btn-outline-primary-2 btn-minwidth-sm">
                                    <div wire:loading.class="d-none">
                                        <span>Kirim Pesan</span>
                                        <i class="icon-long-arrow-right"></i>
                                    </div>
                                    <div wire:loading>
                                        <i class="fa fa-spinner fa-spin"></i>
                                    </div>
                                </button>
                            </div><!-- End .text-center -->
                        </form><!-- End .contact-form -->
                    </div><!-- End .col-md-9 col-lg-7 -->
                </div><!-- End .row -->
            </div>
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
        let lat = {{ store()->latitude }};
        let long = {{ store()->longitude }}
        // Creating map options
        let mapOptions = {
            center: [lat, long],
            zoom: 20
        }

        // Creating a map object
        let map = new L.map('map', mapOptions);

        // Creating a Layer object
        let layer = new L.TileLayer(`http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png`);

        // Adding layer to the map
        map.addLayer(layer);

        let marker = new L.marker([lat, long]);

        marker.addTo(map)
            .bindPopup(`<b>{{ site()->site_name }}</b>
                <hr style="margin:0;line-height:0" /> <br />
                <b>Telp/Hp: </b> {{ store()->phone }} <br />
                <b>Email: </b> {{ store()->email }} <br />
                <b>Alamat: </b> {{ store()->address }} <br />
            `
            , {
                keepInView: true,
                className: 'popup-partner',
            });
    </script>
@endpush
