<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">@yield('code')</li>
            </ol>
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->

    <div class="error-content text-center">
        <div class="container">
            <h1 class="error-title">@yield('code')</h1><!-- End .error-title -->
            <p>@yield('message')</p>
            <a href="{{ route('home') }}" class="btn btn-outline-primary-2 btn-minwidth-lg">
                <span>Kembali ke home </span>
                <i class="icon-long-arrow-right"></i>
            </a>
        </div><!-- End .container -->
    </div><!-- End .error-content text-center -->
</main>
