@extends('layouts.app_bootstrap')
@section('title', 'Admin - Kelola Booking')

@section('content')
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h4 class="mb-1 naisya-title">Kelola Booking</h4>
            <div class="text-white-50 small">Approve / Reject booking dari user.</div>
        </div>

        <div class="naisya-badge">
            <i class="bi bi-info-circle me-1"></i>Tip: gunakan tombol status di kanan
        </div>
    </div>

    <div class="naisya-glass p-3">
        <div class="table-responsive">
            <table class="table naisya-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Lapangan</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $b)
                        <tr class="naisya-fade">
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-semibold">{{ $b->user->name }}</td>
                            <td>{{ $b->field->name }}</td>
                            <td>{{ $b->booking_date }}</td>
                            <td>{{ $b->start_time }} - {{ $b->end_time }}</td>
                            <td>Rp {{ number_format($b->total_price) }}</td>
                            <td>
                                @php
                                    $badge = match ($b->status) {
                                        'paid' => 'success',
                                        'rejected' => 'danger',
                                        'approved_unpaid' => 'primary',
                                        default => 'warning',
                                    };
                                @endphp
                                <span class="badge rounded-pill text-bg-{{ $badge }}">
                                    <i class="bi bi-dot me-1"></i>{{ $b->status }}
                                </span>
                            </td>
                            <td class="text-end">
                                <div class="btn-group" role="group">
                                    <form method="POST" action="{{ route('admin.bookings.status', $b) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="approved_unpaid">
                                        <button class="btn btn-sm btn-outline-success" type="submit">
                                            <i class="bi bi-check2-circle me-1"></i>Approve
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.bookings.status', $b) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="rejected">
                                        <button class="btn btn-sm btn-outline-danger" type="submit">
                                            <i class="bi bi-x-circle me-1"></i>Reject
                                        </button>
                                    </form>

                                    <form method="POST" action="{{ route('admin.bookings.status', $b) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="pending">
                                        <button class="btn btn-sm btn-outline-warning" type="submit">
                                            <i class="bi bi-hourglass-split me-1"></i>Pending
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-white-50 py-4">Belum ada booking.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">{{ $bookings->links() }}</div>
@endsection
