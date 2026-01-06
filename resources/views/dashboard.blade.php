@extends('layouts.app_bootstrap')

@section('title', 'Dashboard')

@section('content')
    <div class="row g-3">
        <div class="col-md-8">
            <div class="p-4 bg-white border rounded">
                <h4 class="mb-1">Dashboard</h4>
                <p class="text-muted mb-0">
                    Selamat datang, <span class="fw-semibold">{{ auth()->user()->name }}</span> 👋
                </p>

                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('fields.index') }}" class="btn btn-primary">Lihat Lapangan</a>
                    <a href="{{ route('bookings.index') }}" class="btn btn-outline-primary">Booking Saya</a>

                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.fields.index') }}" class="btn btn-outline-dark">Admin Panel</a>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="p-4 bg-white border rounded">
                <div class="small text-muted mb-1">Role</div>
                <div class="fw-semibold text-capitalize">{{ auth()->user()->role }}</div>

                <hr>

                <div class="small text-muted mb-1">Akun</div>
                <div>{{ auth()->user()->email }}</div>
            </div>
        </div>
    </div>
@endsection
