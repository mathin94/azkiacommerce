@if ($banners)
    <div class="container new-arrivals">
        <div class="row">
            @foreach ($banners as $item)
                <div class="col-md-4">
                    <div class="banner banner-overlay">
                        <a href="{{ $item->link }}">
                            <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
