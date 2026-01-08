@extends('layouts.app_bootstrap')
@section('title', 'Dashboard')

@section('content')
    <div class="row g-3">
        <div class="col-lg-8">
            <div class="naisya-hero">
                <div class="position-relative">
                    <div class="d-flex align-items-start justify-content-between">
                        <div>
                            <div class="naisya-badge mb-2">
                                <i class="bi bi-speedometer2 me-1"></i> Dashboard
                            </div>
                            <h3 class="fw-bold mb-1 naisya-title">
                                Halo, {{ auth()->user()->name }} 👋
                            </h3>
                            <p class="text-dark mb-0">
                                Kelola booking kamu dengan cepat. Pilih lapangan favorit dan atur jadwal.
                            </p>
                            <div class="d-flex flex-wrap gap-2 mt-3">
                                <a href="{{ route('fields.index') }}" class="btn naisya-btn naisya-btn-primary">
                                    <i class="bi bi-grid-1x2-fill me-2"></i>Lihat Lapangan
                                </a>
                                <a href="{{ route('bookings.index') }}" class="btn naisya-btn naisya-btn-soft">
                                    <i class="bi bi-calendar-check me-2"></i>Booking Saya
                                </a>

                                @if (auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.bookings.index') }}" class="btn naisya-btn naisya-btn-soft">
                                        <i class="bi bi-shield-lock-fill me-2"></i>Admin Panel
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="d-none d-md-block">
                            <div class="naisya-glass p-3">
                                <div class="small text-dark">Role</div>
                                <div class="fw-semibold text-capitalize">{{ auth()->user()->role }}</div>
                                <div class="small text-dark mt-2">Email</div>
                                <div class="small">{{ auth()->user()->email }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="naisya-card p-4">
                <h6 class="mb-3"><i class="bi bi-lightbulb me-2"></i>Tips</h6>
                <div class="text-dark small d-grid gap-2">
                    <div><i class="bi bi-check2 me-2 text-success"></i>Pilih jam yang sesuai & hindari bentrok.</div>
                    <div><i class="bi bi-check2 me-2 text-success"></i>Admin akan mengubah status booking.</div>
                    <div><i class="bi bi-check2 me-2 text-success"></i>Data lapangan bisa diubah lewat menu Admin.</div>
                </div>
            </div>
        </div>
    </div>
@endsection
