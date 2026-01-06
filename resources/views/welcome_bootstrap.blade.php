@extends('layouts.app_bootstrap')

@section('title', 'Beranda - Naisya Sport Booking')

@section('content')
    <div class="p-4 p-md-5 mb-4 bg-white rounded border">
        <h1 class="fw-bold mb-2">Naisya Sport Booking</h1>
        <p class="text-muted mb-0">Booking lapangan jadi lebih cepat, rapi, dan terdata.</p>
    </div>

    <div class="d-flex align-items-center justify-content-between mb-2">
        <h5 class="mb-0">Lapangan Terbaru</h5>
        <a href="{{ route('fields.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
    </div>

    <div class="row g-3">
        @forelse($fields as $field)
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title mb-1">{{ $field->name }}</h5>
                            <span class="badge text-bg-secondary">{{ $field->sport_type }}</span>
                        </div>
                        <div class="text-muted small">{{ $field->location }}</div>
                        <div class="mt-2 fw-semibold">Rp {{ number_format($field->price_per_hour) }}/jam</div>
                        <p class="mt-2 mb-0 small text-muted">
                            {{ \Illuminate\Support\Str::limit($field->description ?? '-', 90) }}
                        </p>
                    </div>
                    <div class="card-footer bg-white border-0">
                        @auth
                            <a class="btn btn-primary w-100" href="{{ route('bookings.create', ['field_id' => $field->id]) }}">
                                Booking Sekarang
                            </a>
                        @else
                            <a class="btn btn-outline-primary w-100" href="{{ route('login') }}">
                                Login untuk Booking
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info">Belum ada data lapangan. Jalankan seeder dulu.</div>
            </div>
        @endforelse
    </div>
@endsection
