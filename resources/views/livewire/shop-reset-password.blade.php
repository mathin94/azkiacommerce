<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Reset Kata Sandi</li>
            </ol>
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->

    <div class="login-page bg-image pt-8 pb-8 pt-md-12 pb-md-12 pt-lg-17 pb-lg-17"
        style="background-image: url('/build/assets/images/backgrounds/login-bg.jpg')">
        <div class="container">
            <div class="form-box">
                <h3 class="title">Reset Kata Sandi</h3>
                <hr>
                <div class="content">
                    <form wire:submit.prevent="submit">
                        @if (session()->has('error'))
                            <div class="alert alert-danger mb-3">
                                {{ session('error') }}
                            </div>
                        @endif

                        <div class="form-group">
                            <label for="singin-email-2">Password *</label>
                            <input type="password" class="form-control" placeholder="Password"
                                wire:model.lazy="password" autofocus>
                            @error('password')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror

                        </div><!-- End .form-group -->
                        <div class="form-group">
                            <label for="singin-email-2">Ulangi Password *</label>
                            <input type="password" class="form-control" placeholder="Ulangi Password"
                                wire:model.lazy="passwordConfirmation" autofocus>
                            @error('passwordConfirmation')
                                <span class="error text-danger">{{ $message }}</span>
                            @enderror

                        </div><!-- End .form-group -->
                        <div class="form-footer">
                            <div class="col-md-7 text-left">
                                <a href="{{ route('login') }}" class="forgot-link"><i class="icon-long-arrow-left"></i>
                                    Kembali ke halaman login</a>
                            </div>
                            <div class="col-md-5 text-right">
                                <button type="submit" class="btn btn-outline-primary-2" wire:target="submit"
                                    wire:loading.attr="disabled">
                                    <div wire:loading.class="d-none" wire:target="submit">
                                        <span>Reset</span>
                                        <i class="icon-long-arrow-right"></i>
                                    </div>
                                    <div wire:loading wire:target="submit">
                                        <x-css-spinner class="fa-spin" />
                                    </div>
                                </button>
                            </div>
                        </div><!-- End .form-footer -->
                    </form>
                </div>
            </div><!-- End .form-box -->
        </div><!-- End .container -->
    </div><!-- End .login-page section-bg -->
</main><!-- End .main -->
