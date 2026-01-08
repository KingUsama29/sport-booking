@extends('layouts.app_bootstrap')
@section('title', 'Booking Saya')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="mb-1 naisya-title">Booking Saya</h4>
            <div class="text-muted small">Riwayat booking, status admin, dan pembayaran.</div>
        </div>

        <a href="{{ route('bookings.create') }}" class="btn naisya-btn naisya-btn-primary">
            <i class="bi bi-calendar-plus me-2"></i>Buat Booking
        </a>
    </div>

    <div class="naisya-glass p-3">
        <div class="table-responsive">
            <table class="table naisya-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Lapangan</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Pembayaran</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $b)
                        <tr class="naisya-fade">
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $b->field->name }}</td>
                            <td>{{ $b->booking_date?->format('d M Y') }}</td>

                            {{-- ✅ Jam per 1 jam --}}
                            <td class="fw-semibold">{{ $b->hourLabel() }}</td>

                            {{-- ✅ total pakai kolom total --}}
                            <td>Rp {{ number_format($b->total, 0, ',', '.') }}</td>

                            @php
                                $badge = match ($b->status) {
                                    'approved_paid', 'paid' => 'success',
                                    'rejected' => 'danger',
                                    'approved_unpaid' => 'primary',
                                    default => 'warning',
                                };
                            @endphp

                            <td>
                                <span class="badge rounded-pill text-bg-{{ $badge }}">
                                    {{ $b->status }}
                                </span>
                            </td>

                            {{-- ✅ kalau payment masih di bookings table --}}
                            <td>
                                @if ($b->payment_status)
                                    <span class="badge rounded-pill text-bg-secondary">
                                        {{ $b->payment_status }}
                                    </span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>

                            <td class="text-end">
                                @if ($b->status === 'approved_unpaid')
                                    <a href="{{ route('payments.checkout', $b) }}"
                                        class="btn btn-sm naisya-btn naisya-btn-primary">
                                        <i class="bi bi-credit-card me-1"></i>Bayar
                                    </a>
                                @elseif($b->status === 'approved_paid' || $b->status === 'paid')
                                    <span class="text-success small fw-semibold">
                                        <i class="bi bi-check-circle me-1"></i>Lunas
                                    </span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="bi bi-inbox me-2"></i>Belum ada booking.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $bookings->links() }}</div>
@endsection
