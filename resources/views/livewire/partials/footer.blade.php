<div>
    <footer class="footer footer-2">
        <div class="footer-middle">
            <div class="container">
                <div class="row">
                    <div class="col-sm-12 col-lg-6">
                        <div class="widget widget-about">
                            <img src="/build/assets/images/logo-color.png" class="footer-logo"
                                alt="Footer Logo" width="82" height="25">
                            <p>azkiahijab.co.id adalah website toko online resmi dari Azkia Hijab </p>

                        </div><!-- End .widget about-widget -->
                    </div><!-- End .col-sm-12 col-lg-3 -->

                    <div class="col-sm-4 col-lg-2 offset-md-2">
                        <div class="widget">
                            <h4 class="widget-title">Informasi</h4><!-- End .widget-title -->

                            <ul class="widget-list">
                                @foreach ($pages as $page)
                                <li><a href="{{ $page->public_url }}">{{ $page->title }}</a></li>
                                @endforeach
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
                                    <li><a href="{{ route('cart') }}">Keranjang Belanja</a></li>
                                    <li><a href="{{ route('customer.wishlist') }}">Wishlist</a></li>
                                    <li><a href="{{ route('customer.orders') }}">Daftar Pesanan</a></li>
                                    <li><a href="#">Bantuan</a></li>
                                @endif
                            </ul><!-- End .widget-list -->
                        </div><!-- End .widget -->
                    </div><!-- End .col-sm-64 col-lg-3 -->
                </div><!-- End .row -->
            </div><!-- End .container -->
        </div><!-- End .footer-middle -->

        <div class="footer-bottom">
            <div class="container">
                <p class="footer-copyright">Copyright © 2023 Azkia Hijab. All Rights Reserved.</p>
                <!-- End .footer-copyright -->
                <ul class="footer-menu">
                    <li><a href="#">Terms Of Use</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                </ul><!-- End .footer-menu -->

                <div class="social-icons social-icons-color">
                    <span class="social-label">Social Media</span>
                    <a href="https://www.facebook.com/azkiahijabofficial" class="social-icon social-facebook"
                        title="Facebook" target="_blank"><i class="icon-facebook-f"></i></a>
                    <a href="https://wa.me/+62895808855575/?text=Halo%20admin%20*Azkia%20Hijab*"
                        class="social-icon social-whatsapp" title="Whatsapp" target="_blank"><i
                            class="icon-whatsapp"></i></a>
                    <a href="https://instagram.com/azkia_hijab_official?igshid=3aa196ry4uo"
                        class="social-icon social-instagram" title="Instagram" target="_blank"><i
                            class="icon-instagram"></i></a>
                    <a href="azkiahijabofficial@gmail.com" class="social-icon social-email" title="Email"
                        target="_blank"><i class="icon-email"></i></a>
                </div><!-- End .soial-icons -->
            </div><!-- End .container -->
        </div><!-- End .footer-bottom -->
    </footer><!-- End .footer -->
</div>
