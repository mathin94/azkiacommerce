@if ($posts)
    <div class="widget">
        <h3 class="widget-title">Artikel Populer</h3>

        <ul class="posts-list">
            @foreach ($posts as $item)
                <li>
                    <figure>
                        <a href="{{ $item->public_url }}">
                            <img src="{{ $item->image_url }}" alt="{{ $item->name }} image">
                        </a>
                    </figure>

                    <div>
                        <span>{{ $item->published_at->format('d M, Y') }}</span>
                        <h4><a href="{{ $item->public_url }}">{{ $item->title }}</a></h4>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
@endif
