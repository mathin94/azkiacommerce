@if ($sliders)
    <div class="container">
        <div class="ads-slider-container">
            <div class="ads-slider owl-carousel mb-5 owl-simple">
                @foreach ($sliders as $item)
                    <a href="{{ $item->link }}" class="brand">
                        <img src="{{ $item->image_url }}" alt="{{ $item->title }}">
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(".ads-slider").owlCarousel({
                nav: true,
                dots: false,
                margin: 10,
                autoplay: true,
                loop: true,
                autoplayTimeout: 5000,
                responsive: {
                    0: {
                        items: 1
                    },
                    720: {
                        items: 2
                    }
                }
            })
        </script>
    @endpush
@endif
