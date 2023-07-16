<div>
    <div class="soon">
        <div class="container">
            <div class="row">
                <div class="col-md-9 col-lg-8">
                    <div class="soon-content text-center">
                        <div class="soon-content-wrapper">
                            <img src="{{ site()->mainLogo() }}" alt="Logo" style="height: 50px;" class="soon-logo mx-auto">
                            <h1 class="soon-title">{{ $title }}</h1><!-- End .soon-title -->
                            <div class="coming-countdown countdown-separator"></div><!-- End .coming-countdown -->
                            <hr class="mt-2 mb-3 mt-md-3">
                            <p class="mb-2">
                                {{ $body_content }}
                            </p>
                            <div class="social-icons justify-content-center mb-0">
                                <a href="#" data-sharer="facebook"
                                    data-title="{{ $title }} di {{ config('app.name') }}"
                                    data-url="{{ route('comingsoon') }}" class="social-icon"
                                    title="Facebook"><x-bi-facebook /></a>
                                <a href="#" data-sharer="twitter"
                                    data-title="{{ $title }} di {{ config('app.name') }}"
                                    data-url="{{ route('comingsoon') }}" class="social-icon" title="Twitter"><x-bi-twitter /></a>
                                <a href="#" data-sharer="instagram"
                                    data-title="{{ $title }} di {{ config('app.name') }}"
                                    data-url="{{ route('comingsoon') }}" class="social-icon"
                                    title="Instagram"><x-bi-instagram /></a>
                                <a href="#" data-sharer="pinterest"
                                    data-title="{{ $title }} di {{ config('app.name') }}"
                                    data-url="{{ route('comingsoon') }}" class="social-icon"
                                    title="Pinterest"><x-bi-pinterest /></a>
                            </div><!-- End .soial-icons -->
                        </div><!-- End .soon-content-wrapper -->
                    </div><!-- End .soon-content -->
                </div><!-- End .col-md-9 col-lg-8 -->
            </div><!-- End .row -->
        </div><!-- End .container -->
        <div class="soon-bg bg-image" style="background-image: url({{ $background_image }})"></div>
    </div>
</div>

@push('scripts')
<script>
    $(function() {
        "use strict";
        if ($.fn.countdown) {
            $('.coming-countdown').countdown({
                until: new Date('{{ $launching_date }}'),
                padZeroes: true
            });
            // Pause
            // $('.coming-countdown').countdown('pause');
        }
    });
</script>
@endpush
