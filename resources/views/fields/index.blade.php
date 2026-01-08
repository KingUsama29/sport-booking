@extends('layouts.app_bootstrap')
@section('title', 'Daftar Lapangan')

@section('content')
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 mb-3">
        <div>
            <h4 class="mb-1 naisya-title">Daftar Lapangan</h4>
            <div class="text-muted small">Pilih lapangan, cek lokasi dan harga, lalu booking.</div>
        </div>

        @auth
            <a href="{{ route('bookings.create') }}" class="btn naisya-btn naisya-btn-primary">
                <i class="bi bi-calendar-plus me-2"></i>Buat Booking
            </a>
        @endauth
    </div>

    <div class="row g-3">
        @foreach ($fields as $field)
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

                    <div class="naisya-card-body">
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

                    <div class="naisya-card-actions mt-auto">
                        @auth
                            <a href="{{ route('bookings.create', ['field_id' => $field->id]) }}"
                                class="btn naisya-btn naisya-btn-primary w-100">
                                <i class="bi bi-calendar-check me-2"></i>Booking
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn naisya-btn naisya-btn-soft w-100">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Login untuk Booking
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $fields->links() }}
    </div>
@endsection
