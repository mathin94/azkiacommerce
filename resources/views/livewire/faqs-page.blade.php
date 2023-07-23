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

                <div class="accordion accordion-rounded" id="accordion-faq">
                    @foreach ($faqs as $item)
                    <div class="card card-box card-sm bg-light">
                        <div class="card-header" id="heading-{{ $item->index }}">
                            <h2 class="card-title">
                                <a class="collapsed" role="button" data-toggle="collapse" href="#collapse-{{ $item->index }}" aria-expanded="false" aria-controls="collapse-{{ $item->index }}">
                                    {{ $item->question }}
                                </a>
                            </h2>
                        </div><!-- End .card-header -->
                        <div id="collapse-{{ $item->index }}" class="collapse" aria-labelledby="heading-{{ $item->index }}" data-parent="#accordion-faq">
                            <div class="card-body">
                                {{ $item->answer }}
                            </div><!-- End .card-body -->
                        </div><!-- End .collapse -->
                    </div><!-- End .card -->
                    @endforeach
                </div>
            </div>
        </div>

        <livewire:contact-us-cta />
    </main>
</div>

@push('scripts')
    <script>
    </script>
@endpush
