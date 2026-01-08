@extends('layouts.auth')
@section('title', 'Login - Naisya Sport Booking')

@section('content')
    <div class="naisya-auth-card">
        <div class="row g-0">
            <div class="col-lg-5">
                <div class="naisya-auth-left">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="naisya-auth-logo"><i class="bi bi-lightning-charge-fill"></i></div>
                        <div class="fw-semibold">Naisya Sport Booking</div>
                    </div>

                    <h3 class="naisya-auth-title mb-2">Selamat datang kembali 👋</h3>
                    <div class="naisya-auth-sub">
                        Login untuk mulai booking lapangan favoritmu dan cek status booking.
                    </div>

                    <div class="naisya-auth-divider"></div>

                    <div class="d-grid gap-2">
                        <div class="d-flex align-items-center gap-2 naisya-auth-hint">
                            <i class="bi bi-check-circle-fill text-success"></i> Booking lapangan lebih cepat
                        </div>
                        <div class="d-flex align-items-center gap-2 naisya-auth-hint">
                            <i class="bi bi-check-circle-fill text-success"></i> Riwayat booking tersimpan rapi
                        </div>
                        <div class="d-flex align-items-center gap-2 naisya-auth-hint">
                            <i class="bi bi-check-circle-fill text-success"></i> Admin memproses status booking
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="naisya-auth-right">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">Login</h4>
                            <div class="text-muted small">Masukkan email dan password kamu.</div>
                        </div>
                        <span class="naisya-badge"><i class="bi bi-shield-lock me-1"></i>Secure</span>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="mt-4">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required
                                autofocus autocomplete="username" placeholder="contoh: user1@test.com">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required
                                autocomplete="current-password" placeholder="••••••••">
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                <label class="form-check-label" for="remember">Remember me</label>
                            </div>


                        </div>

                        <button class="btn naisya-btn naisya-btn-primary w-100" type="submit">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login
                        </button>

                        <div class="text-center mt-3 small text-muted">
                            Belum punya akun?
                            <a class="naisya-link fw-semibold" href="{{ route('register') }}">Register</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
