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

                            <livewire:sharer-cta
                                :title="$title"
                                :url="route('comingsoon')"
                            />
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
