@extends('layouts.app_bootstrap')
@section('title', 'Admin - Kelola Booking')

@section('content')
    <h4 class="mb-3">Kelola Booking</h4>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Lapangan</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th width="220">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $b)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $b->user->name }}</td>
                            <td>{{ $b->field->name }}</td>
                            <td>{{ $b->booking_date }}</td>
                            <td>{{ $b->start_time }} - {{ $b->end_time }}</td>
                            <td>Rp {{ number_format($b->total_price) }}</td>
                            <td>
                                @php
                                    $badge = match ($b->status) {
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                        default => 'warning',
                                    };
                                @endphp
                                <span class="badge text-bg-{{ $badge }}">{{ $b->status }}</span>
                            </td>
                            <td class="d-flex gap-2">
                                <form method="POST" action="{{ route('admin.bookings.status', $b) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="approved">
                                    <button class="btn btn-sm btn-success" type="submit">Approve</button>
                                </form>

                                <form method="POST" action="{{ route('admin.bookings.status', $b) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="rejected">
                                    <button class="btn btn-sm btn-danger" type="submit">Reject</button>
                                </form>

                                <form method="POST" action="{{ route('admin.bookings.status', $b) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="pending">
                                    <button class="btn btn-sm btn-outline-secondary" type="submit">Pending</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">Belum ada booking.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $bookings->links() }}</div>
@endsection
