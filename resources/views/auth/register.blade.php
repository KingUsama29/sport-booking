@extends('layouts.auth')
@section('title', 'Register - Naisya Sport Booking')

@section('content')
    <div class="naisya-auth-card">
        <div class="row g-0">
            <div class="col-lg-5">
                <div class="naisya-auth-left">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="naisya-auth-logo"><i class="bi bi-lightning-charge-fill"></i></div>
                        <div class="fw-semibold">Naisya Sport Booking</div>
                    </div>

                    <h3 class="naisya-auth-title mb-2">Buat akun baru ✨</h3>
                    <div class="naisya-auth-sub">
                        Daftar untuk booking lapangan, simpan riwayat booking, dan pantau statusnya.
                    </div>

                    <div class="naisya-auth-divider"></div>

                    <div class="d-grid gap-2">
                        <div class="d-flex align-items-center gap-2 naisya-auth-hint">
                            <i class="bi bi-check-circle-fill text-success"></i> Proses daftar cepat
                        </div>
                        <div class="d-flex align-items-center gap-2 naisya-auth-hint">
                            <i class="bi bi-check-circle-fill text-success"></i> Data aman dan tersimpan
                        </div>
                        <div class="d-flex align-items-center gap-2 naisya-auth-hint">
                            <i class="bi bi-check-circle-fill text-success"></i> Langsung bisa booking
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="naisya-auth-right">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">Register</h4>
                            <div class="text-muted small">Isi data berikut untuk membuat akun.</div>
                        </div>
                        <span class="naisya-badge"><i class="bi bi-person-plus me-1"></i>New</span>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="mt-4">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required
                                autofocus autocomplete="name" placeholder="Nama lengkap">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required
                                autocomplete="username" placeholder="contoh: user@email.com">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required autocomplete="new-password"
                                placeholder="Minimal 8 karakter">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control" required
                                autocomplete="new-password" placeholder="Ulangi password">
                        </div>

                        <button class="btn naisya-btn naisya-btn-primary w-100" type="submit">
                            <i class="bi bi-check2-circle me-2"></i>Buat Akun
                        </button>

                        <div class="text-center mt-3 small text-muted">
                            Sudah punya akun?
                            <a class="naisya-link fw-semibold" href="{{ route('login') }}">Login</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
