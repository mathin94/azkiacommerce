<main class="main">
    <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
        <div class="container">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Login</li>
            </ol>
        </div><!-- End .container -->
    </nav><!-- End .breadcrumb-nav -->

    <div class="login-page bg-image pt-8 pb-8 pt-md-12 pb-md-12 pt-lg-17 pb-lg-17"
        style="background-image: url('/build/assets/images/backgrounds/login-bg.jpg')">
        <div class="container">
            <div class="form-box">
                <div class="form-tab">
                    <ul class="nav nav-pills nav-fill" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link @if ($tab !== 'register') active @endif" id="signin-tab-2"
                                data-toggle="tab" href="#signin-2" role="tab" aria-controls="signin-2"
                                aria-selected="false" wire:click="updateLayoutData">Masuk</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link @if ($tab === 'register') active @endif" id="register-tab-2"
                                data-toggle="tab" href="#register-2" role="tab" aria-controls="register-2"
                                aria-selected="true" wire:click="updateLayoutData('register')">Daftar</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade @if ($tab !== 'register') show active @endif" id="signin-2"
                            role="tabpanel" aria-labelledby="signin-tab-2">
                            <form wire:submit.prevent="submitLogin">
                                <div class="form-group">
                                    <label for="singin-email-2">Alamat email *</label>
                                    <input type="text" class="form-control" wire:model.lazy="email">
                                    @error('email')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div><!-- End .form-group -->

                                <div class="form-group">
                                    <label for="singin-password-2">Kata Sandi *</label>
                                    <input type="password" class="form-control" wire:model.lazy="password">
                                    @error('password')
                                        <span class="error text-danger">{{ $message }}</span>
                                    @enderror
                                </div><!-- End .form-group -->

                                <div class="form-footer">
                                    <button type="submit" class="btn btn-outline-primary-2" wire:target="submitLogin"
                                        wire:loading.attr="disabled">
                                        <div wire:loading.class="d-none" wire:target="submitLogin">
                                            <span>Login</span>
                                            <i class="icon-long-arrow-right"></i>
                                        </div>
                                        <div wire:loading wire:target="submitLogin">
                                            <x-css-spinner class="fa-spin" />
                                        </div>
                                    </button>

                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" wire:model="remember">
                                        <label class="custom-control-label" for="signin-remember-2">Ingat
                                            Saya</label>
                                    </div><!-- End .custom-checkbox -->

                                    <a href="#" class="forgot-link">Lupa Kata Sandi?</a>
                                </div><!-- End .form-footer -->
                            </form>
                        </div><!-- .End .tab-pane -->
                        <div class="tab-pane fade @if ($tab === 'register') show active @endif" id="register-2"
                            role="tabpanel" aria-labelledby="register-tab-2">
                            <form action="#">
                                <div class="form-group">
                                    <label>Nama Lengkap *</label>
                                    <input type="text" class="form-control" wire:model="fullName">
                                </div><!-- End .form-group -->

                                <div class="form-group">
                                    <label>No Hp / Whatsapp *</label>
                                    <input type="text" class="form-control" wire:model="phoneNumber">
                                </div><!-- End .form-group -->

                                <div class="form-group">
                                    <label>Alamat Email *</label>
                                    <input type="email" class="form-control" wire:model="phoneNumber">
                                </div><!-- End .form-group -->

                                <div class="form-group">
                                    <label>Gender *</label>
                                    <select class="form-control" wire:model="gender">
                                        <option value="">Pilih Gender</option>
                                        <option value="L">Laki - Laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div><!-- End .form-group -->

                                <div class="form-group">
                                    <label>Kata Sandi *</label>
                                    <input type="password" class="form-control" wire:model="registerPassword">
                                </div><!-- End .form-group -->

                                <div class="form-group">
                                    <label>Konfirmasi Kata Sandi *</label>
                                    <input type="password" class="form-control"
                                        wire:model="registerPasswordConfirmation">
                                </div><!-- End .form-group -->

                                <div class="form-footer">
                                    <button type="submit" class="btn btn-outline-primary-2">
                                        <span>Daftar</span>
                                        <i class="icon-long-arrow-right"></i>
                                    </button>

                                    {{-- <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" id="register-policy-2"
                                            required>
                                        <label class="custom-control-label" for="register-policy-2">I agree to the <a
                                                href="#">privacy policy</a> *</label>
                                    </div><!-- End .custom-checkbox --> --}}
                                </div><!-- End .form-footer -->
                            </form>
                        </div><!-- .End .tab-pane -->
                    </div><!-- End .tab-content -->
                </div><!-- End .form-tab -->
            </div><!-- End .form-box -->
        </div><!-- End .container -->
    </div><!-- End .login-page section-bg -->
</main><!-- End .main -->
