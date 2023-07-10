@if ($sliders)
    <div class="intro-slider-container">
        <div class="intro-slider owl-carousel owl-theme owl-nav-inside owl-light mb-0">
            @foreach ($sliders as $item)
                <a href="{{ $item->link }}" class="intro-slide" style="background-image: url({{ $item->image_url }});">
                </a>
            @endforeach
        </div>
        <span class="slider-loader"></span>
    </div>

    @push('scripts')
        <script>
            $(".intro-slider").owlCarousel({
                items: 1,
                autoplay: true,
                loop: true,
                autoplayTimeout: 5000,
                responsiveClass: true
            })
        </script>
    @endpush
@endif
