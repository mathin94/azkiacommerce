@if ($testimonials)
    <div class="container">
        <h2 class="title text-center mb-3">Testimoni Pelanggan</h2>
        <div class="owl-carousel owl-theme owl-testimonials">
            @foreach ($testimonials as $item)
                <blockquote class="testimonial text-center">
                    <img src="{{ $item->image_url }}" alt="user">
                    <p>“ {{ $item->comment }} ”</p>

                    <cite>
                        {{ $item->name }}
                        <span>{{ $item->title }}</span>
                    </cite>
                </blockquote>
            @endforeach
        </div>
    </div>

    <div class="container">
        <hr class="mt-1 mb-6">
    </div>

    @push('scripts')
        <script>
            $(".owl-testimonials").owlCarousel({
                nav: false,
                dots: true,
                margin: 20,
                loop: true,
                responsive: {
                    0: {
                        items: 1
                    },
                    768: {
                        items: 2
                    },
                    992: {
                        items: 3
                    },
                    1200: {
                        items: 3,
                        nav: true
                    }
                }
            })
        </script>
    @endpush
@endif
