@extends('layouts.app_bootstrap')
@section('title', 'Beranda - Naisya Sport Booking')

@section('content')
    <div class="naisya-hero mb-4">
        <div class="position-relative">
            <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between gap-3">
                <div>
                    <div class="d-inline-flex align-items-center gap-2 naisya-badge mb-3">
                        <i class="bi bi-stars"></i>
                        <span>Booking cepat • Data rapi • Modern</span>
                    </div>

                    <h1 class="fw-bold naisya-title mb-2" style="color:#0f172a;">
                        Naisya Sport Booking
                    </h1>

                    <p class="mb-0 text-muted" style="max-width: 740px">
                        Platform sederhana untuk booking lapangan olahraga. Admin bisa mengelola lapangan & booking,
                        user dapat memilih jadwal dan melihat riwayat booking.
                    </p>

                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <a href="{{ route('fields.index') }}" class="btn naisya-btn naisya-btn-primary">
                            <i class="bi bi-grid-1x2-fill me-2"></i>Lihat Lapangan
                        </a>

                        @auth
                            <a href="{{ route('bookings.create') }}" class="btn naisya-btn naisya-btn-soft">
                                <i class="bi bi-calendar-plus me-2"></i>Buat Booking
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn naisya-btn naisya-btn-soft">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login untuk Booking
                            </a>
                        @endauth
                    </div>
                </div>

                <div class="d-none d-lg-block">
                    <div class="naisya-glass p-4" style="min-width: 340px;">
                        <div class="small text-muted">Fitur Utama</div>

                        <div class="mt-3 d-grid gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span class="text-muted">CRUD Lapangan (Admin)</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span class="text-muted">Kelola Status Booking</span>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-check-circle-fill text-success"></i>
                                <span class="text-muted">Riwayat Booking (User)</span>
                            </div>
                        </div>

                        <div class="mt-3 small text-muted">
                            @guest
                                Silakan login untuk mulai booking.
                            @else
                                Kamu login sebagai <span class="fw-semibold text-capitalize">{{ auth()->user()->role }}</span>.
                            @endguest
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h5 class="mb-0"><i class="bi bi-lightning-fill me-2"></i>Lapangan Terbaru</h5>
        <a href="{{ route('fields.index') }}" class="btn btn-sm naisya-btn naisya-btn-soft rounded-pill px-3">
            Lihat Semua
        </a>
    </div>

    <div class="row g-3">
        @forelse($fields as $field)
            <div class="col-md-4 naisya-fade">
                <div class="naisya-card h-100 d-flex flex-column">

                    {{-- IMAGE --}}
                    @if ($field->image)
                        <img src="{{ asset('storage/' . $field->image) }}" class="naisya-card-media"
                            alt="Gambar {{ $field->name }}">
                    @else
                        <div class="naisya-media-fallback">
                            <i class="bi bi-image fs-1"></i>
                        </div>
                    @endif

                    <div class="p-4">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="mb-1">{{ $field->name }}</h5>
                                <div class="text-muted small"><i class="bi bi-geo-alt me-1"></i>{{ $field->location }}</div>
                            </div>
                            <span class="naisya-badge">{{ $field->sport_type }}</span>
                        </div>

                        <div class="mt-3 d-flex align-items-center justify-content-between">
                            <div class="text-muted small">Harga / Jam</div>
                            <div class="fw-semibold">Rp {{ number_format($field->price_per_hour) }}</div>
                        </div>

                        <p class="mt-3 mb-0 text-muted naisya-desc">
                            {{ $field->description ?? '-' }}
                        </p>
                    </div>

                    <div class="p-3 pt-0 mt-auto">
                        @auth
                            <a class="btn naisya-btn naisya-btn-primary w-100"
                                href="{{ route('bookings.create', ['field_id' => $field->id]) }}">
                                <i class="bi bi-calendar-check me-2"></i>Booking Sekarang
                            </a>
                        @else
                            <a class="btn naisya-btn naisya-btn-soft w-100" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login untuk Booking
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-warning">Belum ada data lapangan.</div>
            </div>
        @endforelse
    </div>

@endsection
