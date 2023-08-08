<div>
    <div class="white-popup d-none" id="alert-popup">
        <h5 id="alert-title"></h5>
        <div id="alert-body"></div>
    </div>
    <footer class="footer footer-2">
        <div class="footer-middle">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-lg-6">
                        <div class="widget widget-about">
                            <img src="{{ site()->mainLogo() }}" class="footer-logo"
                                alt="Footer Logo" width="82" height="25">
                            <p class="site-description">{{ site()->site_description }} </p>
                            <div class="widget-about-info">
                                <div class="row">
                                    <div class="col-sm-6 col-md-4">
                                        <span class="widget-about-title">Hubungi Kami Disini</span>
                                        <a href="https://wa.me/{{ store()->phone }}/?text=Halo%20admin%20*Azkia%20Hijab*" target="_blank" style="color: #25D366">{{ store()->phone }}</a>
                                    </div><!-- End .col-sm-6 -->
                                    <div class="col-sm-6 col-md-8">
                                        <span class="widget-about-title">Dukungan Ekspedisi</span>
                                        <figure class="footer-payments">
                                            <img src="{{ asset('build/assets/images/ekspedisi.png') }}" alt="Payment methods" width="272" height="20">
                                        </figure><!-- End .footer-payments -->
                                    </div><!-- End .col-sm-6 -->
                                </div><!-- End .row -->
                            </div>
                        </div><!-- End .widget about-widget -->
                    </div><!-- End .col-sm-12 col-lg-3 -->

                    <div class="col-sm-4 col-lg-2">
                        <div class="widget">
                            <h4 class="widget-title">Kategori</h4><!-- End .widget-title -->

                            <ul class="widget-list">
                                @foreach ($categories as $category)
                                <li><a href="{{ $category->public_url }}">{{ $category->name }}</a></li>
                                @endforeach
                            </ul><!-- End .widget-list -->
                        </div><!-- End .widget -->
                    </div><!-- End .col-sm-4 col-lg-3 -->

                    <div class="col-sm-4 col-lg-2">
                        <div class="widget">
                            <h4 class="widget-title">Informasi</h4><!-- End .widget-title -->

                            <ul class="widget-list">
                                <li><a href="{{ route('faqs') }}">FAQ</a></li>
                                <li><a href="{{ route('partner-location') }}">Peta Mitra</a></li>
                                <li><a href="{{ route('comingsoon') }}">{{ soon()->link_title }}</a></li>
                                @foreach ($pages as $page)
                                <li><a href="{{ $page->public_url }}">{{ $page->title }}</a></li>
                                @endforeach
                                <li><a href="{{ route('contact-us') }}">Hubungi Kami</a></li>
                            </ul><!-- End .widget-list -->
                        </div><!-- End .widget -->
                    </div><!-- End .col-sm-4 col-lg-3 -->

                    <div class="col-sm-4 col-lg-2">
                        <div class="widget">
                            <h4 class="widget-title">Akun Saya</h4><!-- End .widget-title -->

                            <ul class="widget-list">
                                @if (auth()->guard('shop')->guest())
                                    <li><a href="{{ route('login') }}">Login</a></li>
                                @else
                                    <li>
                                        <a href="javascript:void(0);" wire:click="openLogoutModal" style="cursor: pointer">
                                            Keluar
                                        </a>
                                    </li>
                                    <li><a href="{{ route('customer.profile') }}">Profil Saya</a></li>
                                    <li><a href="{{ route('customer.addresses') }}">Daftar Alamat</a></li>
                                @endif
                                <li><a href="{{ route('cart') }}">Keranjang Belanja</a></li>
                                <li><a href="{{ route('customer.wishlist') }}">Wishlist</a></li>
                                <li><a href="{{ route('customer.orders') }}">Daftar Pesanan</a></li>
                            </ul><!-- End .widget-list -->
                        </div><!-- End .widget -->
                    </div><!-- End .col-sm-64 col-lg-3 -->
                </div><!-- End .row -->
            </div><!-- End .container -->
        </div><!-- End .footer-middle -->

        <div class="footer-bottom">
            <div class="container">
                <p class="footer-copyright">Copyright © 2023 {{ site()->site_name }}. All Rights Reserved.</p>
                <!-- End .footer-copyright -->
                {{-- <ul class="footer-menu">
                    <li><a href="#">Terms Of Use</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul><!-- End .footer-menu --> --}}

                <div class="social-icons social-icons-color">
                    <span class="social-label">Social Media</span>
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
            </div><!-- End .container -->
        </div><!-- End .footer-bottom -->
    </footer><!-- End .footer -->
</div>
