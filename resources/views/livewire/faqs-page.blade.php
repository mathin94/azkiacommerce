<div>
    <main class="main">
        <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
            <div class="container">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">FAQ</li>
                </ol>
            </div><!-- End .container -->
        </nav>

        <div class="page-content">
            <div class="container">
                <h2 class="title text-center mb-3">FAQ</h2>

                @foreach ($faqs as $item)
                <div class="accordion accordion-rounded" id="accordion-2">
                    <div class="card card-box card-sm bg-light">
                        <div class="card-header" id="heading2-{{ $item->index }}">
                            <h2 class="card-title">
                                <a class="collapsed" role="button" data-toggle="collapse" href="#collapse2-{{ $item->index }}" aria-expanded="false" aria-controls="collapse2-{{ $item->index }}">
                                    {{ $item->question }}
                                </a>
                            </h2>
                        </div><!-- End .card-header -->
                        <div id="collapse2-{{ $item->index }}" class="collapse" aria-labelledby="heading2-{{ $item->index }}" data-parent="#accordion-2">
                            <div class="card-body">
                                {{ $item->answer }}
                            </div><!-- End .card-body -->
                        </div><!-- End .collapse -->
                    </div><!-- End .card -->
                </div>
                @endforeach
            </div>
        </div>
    </main>
</div>

@push('scripts')
    <script>
    </script>
@endpush
