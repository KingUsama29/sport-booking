@extends('layouts.app_bootstrap')

@section('title', 'Daftar Lapangan')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Daftar Lapangan</h4>
    </div>

    <div class="row g-3">
        @foreach ($fields as $field)
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-1">{{ $field->name }}</h5>
                            <span class="badge text-bg-secondary">{{ $field->sport_type }}</span>
                        </div>
                        <div class="text-muted small">{{ $field->location }}</div>
                        <div class="mt-2 fw-semibold">Rp {{ number_format($field->price_per_hour) }}/jam</div>
                        <p class="mt-2 mb-0 small text-muted">
                            {{ \Illuminate\Support\Str::limit($field->description ?? '-', 100) }}
                        </p>
                    </div>
                    <div class="card-footer bg-white">
                        @auth
                            <a href="{{ route('bookings.create', ['field_id' => $field->id]) }}"
                                class="btn btn-primary w-100">Booking</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">Login</a>
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-3">
        {{ $fields->links() }}
    </div>
@endsection
